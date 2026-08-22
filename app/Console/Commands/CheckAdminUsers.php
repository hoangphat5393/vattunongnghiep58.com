<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckAdminUsers extends Command
{
    protected $signature = 'admin:check-users';

    protected $description = 'Kiểm tra user trong bảng users (dùng cho đăng nhập admin)';

    public function handle(): int
    {
        if (! Schema::hasTable('users')) {
            $this->error('Bảng users không tồn tại. Chạy: php artisan migrate');

            return self::FAILURE;
        }

        $this->info('--- Bảng users (admin đăng nhập từ đây) ---');

        $columns = Schema::getColumnListing('users');
        $this->line('Cột trong bảng: '.implode(', ', $columns));
        $hasUsername = in_array('username', $columns);
        $hasStatusCol = in_array('status', $columns);
        $this->line('Có username: '.($hasUsername ? 'có' : 'không'));
        $this->line('Có status: '.($hasStatusCol ? 'có' : 'không'));
        $this->newLine();

        $users = DB::table('users')->get();
        if ($users->isEmpty()) {
            $this->warn('Chưa có user nào trong bảng users.');
            $this->info('Chạy: php artisan admin:create');

            return self::SUCCESS;
        }

        $this->table(
            ['id', 'username', 'email', 'status', 'fullname', 'có role?'],
            $users->map(function ($u) {
                $roleCheck = 'không';
                if (Schema::hasTable('role_user')) {
                    $count = DB::table('role_user')->where('user_id', $u->id)->count();
                    $roleCheck = $count > 0 ? "có ({$count})" : 'không';
                }

                return [
                    $u->id,
                    $u->username ?? '(null)',
                    $u->email ?? '(null)',
                    isset($u->status) ? (string) $u->status : 'n/a',
                    $u->fullname ?? $u->name ?? '(null)',
                    $roleCheck,
                ];
            })->toArray()
        );

        $this->newLine();
        $active = $users->filter(fn ($u) => (isset($u->status) && (int) $u->status === 1));
        if ($active->isEmpty() && $hasStatusCol) {
            $this->warn('Không có user nào có status = 1. Đăng nhập admin yêu cầu status = 1.');
            $this->info('Cập nhật: UPDATE users SET status = 1 WHERE id = <id>; hoặc chạy: php artisan admin:create --reset');
        }

        $noRole = [];
        foreach ($users as $u) {
            if (Schema::hasTable('role_user')) {
                $has = DB::table('role_user')->where('user_id', $u->id)->exists();
                if (! $has) {
                    $noRole[] = $u->email ?? $u->username ?? $u->id;
                }
            }
        }
        if (! empty($noRole)) {
            $this->warn('User sau chưa gán role (có thể không vào được menu phân quyền): '.implode(', ', $noRole));
            $this->info('Chạy: php artisan admin:create --username=<username> --reset để gán lại role.');
        }

        $this->info('Đăng nhập admin: dùng email hoặc username (nếu có cột username) + mật khẩu. User phải có status = 1.');

        return self::SUCCESS;
    }
}
