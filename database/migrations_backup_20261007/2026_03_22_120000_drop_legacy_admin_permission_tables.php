<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Hai bảng admin_permission / admin_role_permission là schema ACL cũ.
 * Dữ liệu đã được chuyển sang permissions + permission_role trong
 * 2026_02_11_110000_refactor_acl_schema. Ứng dụng runtime chỉ dùng
 * App\Models\Backend\Permission ($table = permissions) và pivot permission_role.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('admin_role_permission');
        Schema::dropIfExists('admin_permission');
    }

    public function down(): void
    {
        // Không tạo lại bảng legacy — cần chạy lại migration cũ 2026_02_11_100000 nếu thật sự rollback toàn bộ ACL.
    }
};
