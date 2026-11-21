<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DatabaseExportService;
use App\Services\DatabaseMaintenanceService;
use App\Services\MysqlExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DatabaseMaintenanceController extends Controller
{
    public function __construct(
        protected DatabaseMaintenanceService $maintenanceService,
        protected MysqlExportService $mysqlExportService,
        protected DatabaseExportService $databaseExportService
    ) {}

    /**
     * Hiển thị bảng điều khiển bảo trì cơ sở dữ liệu.
     */
    public function index(): View
    {
        $stats = $this->maintenanceService->getTableStats();
        $exportStatuses = $this->databaseExportService->getAllStatuses();

        return view('backend.setting.database-maintenance', compact('stats', 'exportStatuses'));
    }

    /**
     * Xử lý tối ưu hóa / reset sequence cho bảng.
     */
    public function reset(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'table' => 'required|string',
        ]);

        try {
            $result = $this->maintenanceService->resetTableSequence($request->table);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result['message'],
                    'data' => $result,
                ]);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('success', $result['message']);
        } catch (\Throwable $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Xuất database theo định dạng được chọn: mysql (.sql), pgsql (.sql) hoặc mongodb (.zip JSON).
     */
    public function export(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'format' => ['required', 'string', 'in:'.implode(',', DatabaseExportService::SUPPORTED_FORMATS)],
        ]);

        $format = (string) $validated['format'];
        $lock = Cache::lock("admin_db_export_lock_{$format}", 180);

        if (! $lock->get()) {
            $message = "Đang có tiến trình xuất định dạng [{$format}] khác đang chạy, vui lòng đợi hoàn tất rồi thử lại.";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                ], 423);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('error', $message);
        }

        try {
            $meta = $this->databaseExportService->export($format);

            Log::info('Admin exported database dump', [
                'admin_id' => Auth::guard('admin')->id(),
                'ip' => $request->ip(),
                'format' => $format,
                'filename' => $meta['filename'],
                'size_bytes' => $meta['size_bytes'],
                'duration_ms' => $meta['duration_ms'],
            ]);

            $label = strtoupper($format);
            $message = "Đã xuất database chuẩn {$label} thành công ({$meta['size_human']}).";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $message,
                    'data' => [
                        'format' => $format,
                        'filename' => $meta['filename'],
                        'size_human' => $meta['size_human'],
                        'updated_at' => $meta['updated_at'],
                        'duration_ms' => $meta['duration_ms'],
                        'download_url' => route('admin.database-maintenance.download', ['format' => $format]),
                    ],
                ]);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Admin database export failed', [
                'admin_id' => Auth::guard('admin')->id(),
                'ip' => $request->ip(),
                'format' => $format,
                'error' => $e->getMessage(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('error', $e->getMessage());
        } finally {
            $lock->release();
        }
    }

    /**
     * Tải xuống file dump đã xuất gần nhất theo định dạng (mysql / pgsql / mongodb).
     */
    public function download(Request $request, string $format): BinaryFileResponse|RedirectResponse
    {
        if (! in_array($format, DatabaseExportService::SUPPORTED_FORMATS, true)) {
            abort(404);
        }

        $path = $this->databaseExportService->getDumpPath($format);

        if (! File::exists($path)) {
            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('error', "Chưa có file xuất chuẩn [{$format}]. Vui lòng nhấn \"Xuất & Tải Database\" trước.");
        }

        $descriptor = $this->databaseExportService->getDownloadDescriptor($format);

        Log::info('Admin downloaded database dump', [
            'admin_id' => Auth::guard('admin')->id(),
            'ip' => $request->ip(),
            'format' => $format,
            'size_bytes' => File::size($path),
        ]);

        return response()->download($path, $descriptor['filename'], [
            'Content-Type' => $descriptor['content_type'],
        ]);
    }

    /**
     * Legacy endpoint: giữ tương thích ngược cho route admin.database-maintenance.export-mysql.
     */
    public function exportMysql(Request $request): JsonResponse|RedirectResponse
    {
        $lock = Cache::lock('admin_mysql_export_lock', 120);

        if (! $lock->get()) {
            $message = 'Đang có tiến trình xuất CSDL MySQL khác đang chạy, vui lòng đợi hoàn tất rồi thử lại.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                ], 423);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('error', $message);
        }

        try {
            $meta = $this->mysqlExportService->export();

            Log::info('Admin exported MySQL dump', [
                'admin_id' => Auth::guard('admin')->id(),
                'ip' => $request->ip(),
                'filename' => $meta['filename'],
                'size_bytes' => $meta['size_bytes'],
                'duration_ms' => $meta['duration_ms'],
            ]);

            $message = "Đã đồng bộ & xuất file SQL MySQL thành công ({$meta['size_human']}).";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $message,
                    'data' => [
                        'filename' => $meta['filename'],
                        'size_human' => $meta['size_human'],
                        'updated_at' => $meta['updated_at'],
                        'duration_ms' => $meta['duration_ms'],
                        'download_url' => route('admin.database-maintenance.download-mysql'),
                    ],
                ]);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Admin MySQL export failed', [
                'admin_id' => Auth::guard('admin')->id(),
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('error', $e->getMessage());
        } finally {
            $lock->release();
        }
    }

    /**
     * Legacy endpoint: giữ tương thích ngược cho route admin.database-maintenance.download-mysql.
     */
    public function downloadMysql(Request $request): BinaryFileResponse|RedirectResponse
    {
        $path = $this->mysqlExportService->getDumpPath();

        if (! File::exists($path)) {
            return redirect()
                ->route('admin.database-maintenance.index')
                ->with('error', 'Chưa có file SQL MySQL nào được xuất. Vui lòng nhấn "Đồng bộ & Xuất SQL MySQL" trước.');
        }

        Log::info('Admin downloaded MySQL dump', [
            'admin_id' => Auth::guard('admin')->id(),
            'ip' => $request->ip(),
            'size_bytes' => File::size($path),
        ]);

        $downloadName = 'vattunongnghiep58_mysql_'.now()->format('Ymd_His').'.sql';

        return response()->download($path, $downloadName, [
            'Content-Type' => 'application/sql; charset=UTF-8',
        ]);
    }
}
