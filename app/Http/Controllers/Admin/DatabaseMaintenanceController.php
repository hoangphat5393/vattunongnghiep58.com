<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DatabaseMaintenanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DatabaseMaintenanceController extends Controller
{
    public function __construct(
        protected DatabaseMaintenanceService $maintenanceService
    ) {}

    /**
     * Hiển thị bảng điều khiển bảo trì cơ sở dữ liệu.
     */
    public function index(): View
    {
        $stats = $this->maintenanceService->getTableStats();

        return view('backend.setting.database-maintenance', compact('stats'));
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
}
