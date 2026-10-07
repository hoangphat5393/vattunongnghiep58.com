<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncDatabaseToPostgres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync-to-postgres {--table= : Sync a specific table only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all data from MySQL to PostgreSQL with type conversion and sequence resetting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== BẮT ĐẦU CHUYỂN DỮ LIỆU TỪ MYSQL SANG POSTGRESQL ===');

        // 1. Kiểm tra kết nối cả 2 bên
        try {
            DB::connection('mysql')->getPdo();
            $this->info('✅ Kết nối MySQL thành công.');
        } catch (\Exception $e) {
            $this->error('❌ Lỗi kết nối MySQL: '.$e->getMessage());

            return 1;
        }

        try {
            DB::connection('pgsql')->getPdo();
            $this->info('✅ Kết nối PostgreSQL thành công.');
        } catch (\Exception $e) {
            $this->error('❌ Lỗi kết nối PostgreSQL: '.$e->getMessage());

            return 1;
        }

        // 2. Lấy danh sách bảng trong PostgreSQL
        $singleTable = $this->option('table');

        if ($singleTable) {
            $tables = [$singleTable];
        } else {
            $pgTables = DB::connection('pgsql')->select("
                SELECT tablename 
                FROM pg_catalog.pg_tables 
                WHERE schemaname = 'public' 
                  AND tablename != 'migrations'
                ORDER BY tablename ASC
            ");
            $tables = array_map(fn ($t) => $t->tablename, $pgTables);
        }

        $this->info('Tìm thấy '.count($tables).' bảng cần đồng bộ.');

        $summary = [];

        foreach ($tables as $table) {
            $this->line("\n--------------------------------------------------");
            $this->info("Đang xử lý bảng: <comment>{$table}</comment>");

            // Kiểm tra bảng có tồn tại trong MySQL không
            $hasMySQLTable = Schema::connection('mysql')->hasTable($table);
            if (! $hasMySQLTable) {
                $this->warn("⚠️ Bảng [{$table}] không tồn tại trên MySQL. Bỏ qua.");
                $summary[] = [
                    'table' => $table,
                    'mysql_count' => 0,
                    'pgsql_count' => 0,
                    'status' => 'Skipped (No MySQL table)',
                ];

                continue;
            }

            // Đếm số dòng trên MySQL
            $mysqlCount = DB::connection('mysql')->table($table)->count();
            $this->line("Số dòng trên MySQL: <info>{$mysqlCount}</info>");

            // Lấy thông tin các cột boolean và timestamp trên PostgreSQL để chuyển đổi kiểu
            $pgColumns = DB::connection('pgsql')->select("
                SELECT column_name, data_type 
                FROM information_schema.columns 
                WHERE table_schema = 'public' AND table_name = ?
            ", [$table]);

            $booleanCols = [];
            $dateCols = [];
            foreach ($pgColumns as $col) {
                if ($col->data_type === 'boolean') {
                    $booleanCols[] = $col->column_name;
                }
                if (in_array($col->data_type, ['timestamp without time zone', 'timestamp with time zone', 'date'])) {
                    $dateCols[] = $col->column_name;
                }
            }

            // Xóa dữ liệu cũ trong bảng PostgreSQL
            DB::connection('pgsql')->statement("TRUNCATE TABLE \"{$table}\" CASCADE;");

            if ($mysqlCount === 0) {
                $this->line('Bảng rỗng, đã làm sạch.');
                $summary[] = [
                    'table' => $table,
                    'mysql_count' => 0,
                    'pgsql_count' => 0,
                    'status' => 'Empty (OK)',
                ];

                continue;
            }

            // Bơm dữ liệu theo từng lô (chunk)
            $bar = $this->output->createProgressBar($mysqlCount);
            $bar->start();

            $chunkSize = 300;
            $offset = 0;

            while ($offset < $mysqlCount) {
                $rows = DB::connection('mysql')
                    ->table($table)
                    ->offset($offset)
                    ->limit($chunkSize)
                    ->get();

                if ($rows->isEmpty()) {
                    break;
                }

                $insertData = [];
                foreach ($rows as $row) {
                    $rowArray = (array) $row;

                    // Chuyển đổi dữ liệu tương thích PostgreSQL
                    foreach ($rowArray as $key => $val) {
                        // Xử lý boolean
                        if (in_array($key, $booleanCols)) {
                            if ($val === null) {
                                $rowArray[$key] = null;
                            } else {
                                $rowArray[$key] = (bool) $val;
                            }
                        }

                        // Xử lý ngày tháng rỗng hoặc sai cú pháp
                        if (in_array($key, $dateCols)) {
                            if ($val === '' || $val === '0000-00-00 00:00:00' || $val === '0000-00-00') {
                                $rowArray[$key] = null;
                            }
                        }
                    }

                    $insertData[] = $rowArray;
                }

                DB::connection('pgsql')->table($table)->insert($insertData);

                $bar->advance(count($rows));
                $offset += $chunkSize;
            }

            $bar->finish();
            $this->line('');

            // Đặt lại auto-increment sequence nếu bảng có khóa chính
            $this->resetSequence($table);

            // Kiểm tra số dòng sau khi copy
            $pgCount = DB::connection('pgsql')->table($table)->count();
            $status = ($mysqlCount === $pgCount) ? '✅ Khớp 100%' : '⚠️ Lệch số dòng';
            $this->line("Đã nạp vào PostgreSQL: <info>{$pgCount}</info> dòng ({$status})");

            $summary[] = [
                'table' => $table,
                'mysql_count' => $mysqlCount,
                'pgsql_count' => $pgCount,
                'status' => $status,
            ];
        }

        // In bảng đối soát tổng kết
        $this->line("\n==================================================");
        $this->info('BẢNG ĐỐI SOÁT TỔNG KẾT SAU KHI ĐỒNG BỘ DỮ LIỆU:');
        $this->table(['Bảng', 'Dòng MySQL', 'Dòng PostgreSQL', 'Trạng thái'], $summary);

        $this->info("\n🎉 ĐỒNG BỘ DỮ LIỆU SANG POSTGRESQL HOÀN TẤT THÀNH CÔNG!");

        return 0;
    }

    /**
     * Đặt lại sequence tự tăng cho bảng PostgreSQL
     */
    protected function resetSequence(string $table): void
    {
        try {
            // Tìm cột sequence của bảng
            $seqInfo = DB::connection('pgsql')->select("
                SELECT column_name, pg_get_serial_sequence(?, column_name) as seq_name
                FROM information_schema.columns 
                WHERE table_schema = 'public' 
                  AND table_name = ?
                  AND pg_get_serial_sequence(?, column_name) IS NOT NULL
            ", [$table, $table, $table]);

            foreach ($seqInfo as $info) {
                if (! empty($info->seq_name) && ! empty($info->column_name)) {
                    $col = $info->column_name;
                    $seq = $info->seq_name;
                    DB::connection('pgsql')->statement("
                        SELECT setval('{$seq}', COALESCE((SELECT MAX(\"{$col}\") FROM \"{$table}\"), 1));
                    ");
                }
            }
        } catch (\Exception $e) {
            // Không phải bảng nào cũng có serial sequence (vd: pivot tables), bỏ qua an toàn
        }
    }
}
