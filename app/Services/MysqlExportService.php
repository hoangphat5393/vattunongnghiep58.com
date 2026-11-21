<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class MysqlExportService
{
    /**
     * Đường dẫn file dump MySQL mặc định do lệnh db:sync-to-mysql sinh ra.
     */
    public function getDumpPath(): string
    {
        return database_path('vattunongnghiep58_mysql_latest.sql');
    }

    /**
     * Lấy trạng thái kết nối 2 CSDL và thông tin bản xuất SQL gần nhất.
     *
     * @return array{
     *     pgsql_connected: bool,
     *     mysql_connected: bool,
     *     dump_exists: bool,
     *     filename: string,
     *     size_bytes: int,
     *     size_human: string|null,
     *     updated_at: string|null
     * }
     */
    public function getStatus(): array
    {
        $path = $this->getDumpPath();
        $exists = File::exists($path);
        $sizeBytes = $exists ? (int) File::size($path) : 0;
        $updatedAt = $exists
            ? Carbon::createFromTimestamp(File::lastModified($path))->format('d/m/Y H:i:s')
            : null;

        return [
            'pgsql_connected' => $this->canConnect('pgsql'),
            'mysql_connected' => $this->canConnect('mysql_sync'),
            'dump_exists' => $exists,
            'filename' => basename($path),
            'size_bytes' => $sizeBytes,
            'size_human' => $exists ? $this->formatBytes($sizeBytes) : null,
            'updated_at' => $updatedAt,
        ];
    }

    /**
     * Thực thi đồng bộ PostgreSQL -> MySQL và sinh file dump .sql chuẩn MySQL.
     *
     * @return array{
     *     path: string,
     *     filename: string,
     *     size_bytes: int,
     *     size_human: string,
     *     updated_at: string,
     *     duration_ms: int
     * }
     */
    public function export(): array
    {
        @set_time_limit(180);

        $startedAt = microtime(true);
        $exitCode = Artisan::call('db:sync-to-mysql');
        $output = trim(Artisan::output());
        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

        if ($exitCode !== 0) {
            throw new RuntimeException($this->extractErrorMessage($output));
        }

        $path = $this->getDumpPath();
        if (! File::exists($path) || File::size($path) === 0) {
            throw new RuntimeException('Không tìm thấy file SQL đầu ra sau khi đồng bộ. Vui lòng kiểm tra cấu hình mysqldump.');
        }

        $sizeBytes = (int) File::size($path);

        return [
            'path' => $path,
            'filename' => basename($path),
            'size_bytes' => $sizeBytes,
            'size_human' => $this->formatBytes($sizeBytes),
            'updated_at' => Carbon::createFromTimestamp(File::lastModified($path))->format('d/m/Y H:i:s'),
            'duration_ms' => $durationMs,
        ];
    }

    /**
     * Kiểm tra kết nối PDO an toàn cho một connection trong config/database.php.
     */
    protected function canConnect(string $connection): bool
    {
        try {
            DB::connection($connection)->getPdo();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Trích xuất thông báo lỗi ngắn gọn từ output của Artisan command.
     */
    protected function extractErrorMessage(string $output): string
    {
        if ($output === '') {
            return 'Đồng bộ dữ liệu thất bại. Vui lòng kiểm tra kết nối PostgreSQL và MySQL.';
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $output))));
        foreach (array_reverse($lines) as $line) {
            if (str_contains($line, '❌') || str_contains(strtolower($line), 'lỗi') || str_contains(strtolower($line), 'error')) {
                return $line;
            }
        }

        return end($lines) ?: 'Đồng bộ dữ liệu thất bại.';
    }

    /**
     * Định dạng dung lượng file thân thiện với người dùng.
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }
}
