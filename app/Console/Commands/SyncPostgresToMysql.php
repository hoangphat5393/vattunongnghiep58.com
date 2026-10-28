<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncPostgresToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync-to-mysql {--table= : Sync a specific table only} {--skip-dump : Skip generating mysqldump file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all data from PostgreSQL to MySQL with type conversion and auto-increment resetting';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== BẮT ĐẦU ĐỒNG BỘ DỮ LIỆU TỪ POSTGRESQL SANG MYSQL ===');

        // 1. Kiểm tra kết nối cả 2 bên
        try {
            DB::connection('pgsql')->getPdo();
            $this->info('✅ Kết nối PostgreSQL thành công.');
        } catch (\Exception $e) {
            $this->error('❌ Lỗi kết nối PostgreSQL: '.$e->getMessage());

            return 1;
        }

        try {
            DB::connection('mysql_sync')->getPdo();
            $this->info('✅ Kết nối MySQL (mysql_sync) thành công.');
        } catch (\Exception $e) {
            $this->error('❌ Lỗi kết nối MySQL: '.$e->getMessage());

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

        // 3. Tắt kiểm tra khóa ngoại trên MySQL
        DB::connection('mysql_sync')->statement('SET FOREIGN_KEY_CHECKS=0;');

        $summary = [];

        foreach ($tables as $table) {
            $this->line("\n--------------------------------------------------");
            $this->info("Đang xử lý bảng: <comment>{$table}</comment>");

            // Kiểm tra bảng có tồn tại trong MySQL không
            $hasMySQLTable = Schema::connection('mysql_sync')->hasTable($table);
            if (! $hasMySQLTable) {
                $this->warn("⚠️ Bảng [{$table}] không tồn tại trên MySQL. Bỏ qua.");
                $summary[] = [
                    'table' => $table,
                    'pgsql_count' => 0,
                    'mysql_count' => 0,
                    'status' => 'Skipped (No MySQL table)',
                ];

                continue;
            }

            // Đếm số dòng trên PostgreSQL
            $pgCount = DB::connection('pgsql')->table($table)->count();
            $this->line("Số dòng trên PostgreSQL: <info>{$pgCount}</info>");

            // Lấy danh sách cột của MySQL để tránh insert cột không tồn tại
            $mysqlCols = Schema::connection('mysql_sync')->getColumnListing($table);

            // Xóa dữ liệu cũ trong bảng MySQL
            DB::connection('mysql_sync')->table($table)->truncate();

            if ($pgCount === 0) {
                $this->line('Bảng rỗng, đã làm sạch.');
                $summary[] = [
                    'table' => $table,
                    'pgsql_count' => 0,
                    'mysql_count' => 0,
                    'status' => 'Empty (OK)',
                ];

                continue;
            }

            // Bơm dữ liệu theo từng lô (chunk)
            $bar = $this->output->createProgressBar($pgCount);
            $bar->start();

            $chunkSize = 300;
            $offset = 0;

            while ($offset < $pgCount) {
                $rows = DB::connection('pgsql')
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
                    $filteredRow = [];

                    foreach ($rowArray as $key => $val) {
                        // Chỉ lấy các cột có trên bảng MySQL
                        if (! in_array($key, $mysqlCols)) {
                            continue;
                        }

                        // Xử lý boolean từ pgsql sang tinyint(1) cho MySQL
                        if (is_bool($val)) {
                            $val = $val ? 1 : 0;
                        }

                        // Xử lý mảng/đối tượng JSON
                        if (is_array($val) || is_object($val)) {
                            $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                        }

                        $filteredRow[$key] = $val;
                    }

                    $insertData[] = $filteredRow;
                }

                DB::connection('mysql_sync')->table($table)->insert($insertData);

                $bar->advance(count($rows));
                $offset += $chunkSize;
            }

            $bar->finish();
            $this->line('');

            // Đặt lại AUTO_INCREMENT cho bảng MySQL nếu có khóa chính tự tăng
            $this->resetMySqlAutoIncrement($table);

            // Kiểm tra số dòng sau khi copy
            $mysqlCount = DB::connection('mysql_sync')->table($table)->count();
            $status = ($pgCount === $mysqlCount) ? '✅ Khớp 100%' : '⚠️ Lệch số dòng';
            $this->line("Đã nạp vào MySQL: <info>{$mysqlCount}</info> dòng ({$status})");

            $summary[] = [
                'table' => $table,
                'pgsql_count' => $pgCount,
                'mysql_count' => $mysqlCount,
                'status' => $status,
            ];
        }

        // 4. Bật lại kiểm tra khóa ngoại trên MySQL
        DB::connection('mysql_sync')->statement('SET FOREIGN_KEY_CHECKS=1;');

        // In bảng đối soát tổng kết
        $this->line("\n==================================================");
        $this->info('BẢNG ĐỐI SOÁT TỔNG KẾT SAU KHI ĐỒNG BỘ DỮ LIỆU:');
        $this->table(['Bảng', 'Dòng PostgreSQL', 'Dòng MySQL', 'Trạng thái'], $summary);

        // 5. Xuất file dump SQL MySQL nếu không chọn skip
        if (! $this->option('skip-dump')) {
            $this->exportMySqlDump();
        }

        $this->info("\n🎉 ĐỒNG BỘ DỮ LIỆU SANG MYSQL HOÀN TẤT THÀNH CÔNG!");

        return 0;
    }

    /**
     * Đặt lại auto-increment cho bảng MySQL
     */
    protected function resetMySqlAutoIncrement(string $table): void
    {
        try {
            $maxId = DB::connection('mysql_sync')->table($table)->max('id');
            if ($maxId !== null && is_numeric($maxId)) {
                $nextId = (int) $maxId + 1;
                DB::connection('mysql_sync')->statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$nextId};");
            }
        } catch (\Exception $e) {
            // Bỏ qua nếu bảng không có cột id tự tăng
        }
    }

    /**
     * Xuất file dump SQL định dạng MySQL mới nhất
     */
    protected function exportMySqlDump(): void
    {
        $dumpPath = database_path('vattunongnghiep58_mysql_latest.sql');
        $mysqldumpBin = 'E:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\\mysqldump.exe';

        $dbName = config('database.connections.mysql_sync.database', 'vattunnongnghiep58');
        $user = config('database.connections.mysql_sync.username', 'root');
        $pass = config('database.connections.mysql_sync.password', '1');
        $host = config('database.connections.mysql_sync.host', '127.0.0.1');
        $port = config('database.connections.mysql_sync.port', 3306);

        if (! file_exists($mysqldumpBin)) {
            $mysqldumpBin = 'mysqldump';
        }

        $this->info("\nĐang xuất file SQL dump: {$dumpPath}...");
        $cmd = sprintf(
            '"%s" -h%s -P%s -u%s -p%s %s --result-file="%s"',
            $mysqldumpBin,
            $host,
            $port,
            $user,
            $pass,
            $dbName,
            $dumpPath
        );

        exec($cmd, $output, $returnCode);

        if ($returnCode === 0 && file_exists($dumpPath)) {
            $size = round(filesize($dumpPath) / 1024 / 1024, 2);
            $this->info("✅ Đã xuất thành công file dump: [database/vattunongnghiep58_mysql_latest.sql] ({$size} MB)");
        } else {
            $this->warn('⚠️ Không thể xuất tự động mysqldump (mã lỗi: '.$returnCode.').');
        }
    }
}
