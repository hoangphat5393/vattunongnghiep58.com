<?php

namespace App\Console\Commands;

use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create 
                            {--username=admin : Username for admin}
                            {--email= : Email (default: username@local)}
                            {--password=admin123 : Password}
                            {--reset : Update password if user exists}';

    protected $description = 'Tạo hoặc reset tài khoản admin (bảng users) để đăng nhập /admin';

    public function handle(): int
    {
        $username = $this->option('username') ?: 'admin';
        $email = $this->option('email') ?: $username.'@local';
        $password = $this->option('password') ?: 'admin123';
        $reset = $this->option('reset');

        if (! Schema::hasTable('users')) {
            $this->error('Bảng users không tồn tại. Chạy: php artisan migrate');

            return self::FAILURE;
        }

        $user = User::where('username', $username)->orWhere('email', $email)->first();

        if ($user) {
            if (! $reset) {
                $this->warn("Tài khoản đã tồn tại: {$user->username} (id: {$user->id}). Dùng --reset để đổi mật khẩu.");
                $this->info('Đảm bảo user có status = 1 và có role administrator để đăng nhập admin.');
                $this->ensureAdminRole($user);

                return self::SUCCESS;
            }
            $user->password = Hash::make($password);
            $user->status = 1;
            $user->save();
            $this->ensureAdminRole($user);
            $this->info("Đã cập nhật mật khẩu và status cho user: {$user->username}");

            return self::SUCCESS;
        }

        $data = [
            'email' => $email,
            'password' => Hash::make($password),
            'fullname' => 'Administrator',
            'status' => 1,
        ];
        if (Schema::hasColumn('users', 'username')) {
            $data['username'] = $username;
        }
        if (Schema::hasColumn('users', 'name')) {
            $data['name'] = $username;
        }

        $user = User::create($data);
        $this->ensureAdminRole($user);
        $this->info("Đã tạo admin: username={$username}, email={$email}. Mật khẩu: {$password}");

        return self::SUCCESS;
    }

    protected function ensureAdminRole(User $user): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return;
        }

        $role = null;
        if (Schema::hasColumn('roles', 'slug')) {
            $role = Role::where('slug', 'administrator')->first();
        }
        if (! $role && Schema::hasColumn('roles', 'name')) {
            $role = Role::where('name', 'Administrator')->first();
        }
        if (! $role) {
            $role = Role::first();
        }

        if (! $role) {
            $data = ['name' => 'Administrator'];
            if (Schema::hasColumn('roles', 'slug')) {
                $data['slug'] = 'administrator';
            }
            if (Schema::hasColumn('roles', 'description')) {
                $data['description'] = 'System Administrator';
            }
            $role = Role::create($data);
        }

        if (! $user->roles()->where('roles.id', $role->id)->exists()) {
            $user->roles()->attach($role->id);
            $this->info("Đã gán role 'administrator' cho user.");
        }
    }
}
