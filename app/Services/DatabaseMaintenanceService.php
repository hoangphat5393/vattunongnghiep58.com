<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseMaintenanceService
{
    /**
     * Danh sách các bảng chính được phép bảo trì sequence.
     *
     * @var array<string, string> Table name => Primary key column
     */
    protected array $supportedTables = [
        'products' => 'id',
        'categories' => 'id',
        'pages' => 'id',
        'shop_orders' => 'cart_id',
        'shop_order_items' => 'id',
        'contacts' => 'id',
        'menus' => 'id',
        'menu_items' => 'id',
        'albums' => 'id',
        'album_items' => 'id',
        'users' => 'id',
        'roles' => 'id',
        'permissions' => 'id',
        'email_templates' => 'id',
    ];

    /**
     * Lấy danh sách thống kê trạng thái của các bảng.
     */
    public function getTableStats(): array
    {
        $driver = DB::connection()->getDriverName();
        $stats = [];

        foreach ($this->supportedTables as $table => $pk) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $count = DB::table($table)->count();
            $maxId = DB::table($table)->max($pk) ?? 0;
            $nextId = $maxId + 1;
            $sequenceName = null;

            if ($driver === 'pgsql') {
                $seqRow = DB::selectOne('SELECT pg_get_serial_sequence(?, ?) as seq', [$table, $pk]);
                $sequenceName = $seqRow?->seq;

                if ($sequenceName) {
                    $currValRow = DB::selectOne("SELECT last_value, is_called FROM {$sequenceName}");
                    if ($currValRow) {
                        $nextId = $currValRow->is_called ? ((int) $currValRow->last_value + 1) : (int) $currValRow->last_value;
                    }
                }
            } elseif ($driver === 'mysql') {
                $dbName = DB::connection()->getDatabaseName();
                $aiRow = DB::selectOne('
                    SELECT AUTO_INCREMENT as next_ai 
                    FROM information_schema.tables 
                    WHERE table_schema = ? AND table_name = ?
                ', [$dbName, $table]);
                if ($aiRow && $aiRow->next_ai !== null) {
                    $nextId = (int) $aiRow->next_ai;
                }
            }

            $stats[] = [
                'table' => $table,
                'pk' => $pk,
                'total_rows' => $count,
                'max_id' => $maxId,
                'next_id' => $nextId,
                'sequence_name' => $sequenceName,
                'can_reset_to_one' => ($count === 0),
            ];
        }

        return $stats;
    }

    /**
     * Tối ưu hóa hoặc reset sequence cho bảng được chỉ định.
     */
    public function resetTableSequence(string $table): array
    {
        if (! array_key_exists($table, $this->supportedTables)) {
            throw new \InvalidArgumentException("Bảng [{$table}] không thuộc danh sách hỗ trợ bảo trì.");
        }

        if (! Schema::hasTable($table)) {
            throw new \RuntimeException("Bảng [{$table}] không tồn tại trên cơ sở dữ liệu.");
        }

        $pk = $this->supportedTables[$table];
        $driver = DB::connection()->getDriverName();
        $count = DB::table($table)->count();
        $maxId = DB::table($table)->max($pk) ?? 0;

        if ($driver === 'pgsql') {
            $seqRow = DB::selectOne('SELECT pg_get_serial_sequence(?, ?) as seq', [$table, $pk]);
            $seq = $seqRow?->seq;

            if ($seq) {
                if ($count === 0) {
                    // Bảng trống: Reset về 1, is_called = false để bản ghi tiếp theo nhận ID = 1
                    DB::statement('SELECT setval(?, 1, false)', [$seq]);
                    $newNextId = 1;
                } else {
                    // Bảng có dữ liệu: Đặt bằng maxId, is_called = true để bản ghi tiếp theo nhận maxId + 1
                    DB::statement('SELECT setval(?, ?, true)', [$seq, $maxId]);
                    $newNextId = $maxId + 1;
                }
            } else {
                $newNextId = $maxId + 1;
            }
        } elseif ($driver === 'mysql') {
            $newNextId = $count === 0 ? 1 : ($maxId + 1);
            DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$newNextId}");
        } else {
            // SQLite (dành cho môi trường test)
            if ($count === 0) {
                DB::table('sqlite_sequence')->where('name', $table)->delete();
                $newNextId = 1;
            } else {
                DB::table('sqlite_sequence')->where('name', $table)->update(['seq' => $maxId]);
                $newNextId = $maxId + 1;
            }
        }

        return [
            'table' => $table,
            'pk' => $pk,
            'total_rows' => $count,
            'max_id' => $maxId,
            'next_id' => $newNextId,
            'message' => $count === 0
                ? "Bảng [{$table}] rỗng: đã reset ID về 1 an toàn."
                : "Bảng [{$table}] có {$count} dòng: đã đồng bộ ID tiếp theo là {$newNextId}.",
        ];
    }
}
