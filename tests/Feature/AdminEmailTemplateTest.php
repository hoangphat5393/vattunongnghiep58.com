<?php

namespace Tests\Feature;

use App\Models\Backend\EmailTemplate;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Tests\TestCase;

class AdminEmailTemplateTest extends TestCase
{
    private function createAdministrator(): User
    {
        $admin = User::create([
            'name' => 'Email Template Admin',
            'username' => 'email_tpl_'.time(),
            'email' => 'email_tpl_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    public function test_admin_can_store_email_template_with_text(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.email-template.store'), [
                'name' => 'Chào mừng đăng ký',
                'code' => 'customer_register',
                'text' => '<p>Xin chào {name}</p>',
                'status' => 1,
                'submit' => 'save',
            ])
            ->assertRedirect(route('admin.email-template.index'));

        $this->assertDatabaseHas('email_templates', [
            'name' => 'Chào mừng đăng ký',
            'code' => 'customer_register',
            'text' => '<p>Xin chào {name}</p>',
            'status' => 1,
        ]);
    }

    public function test_store_rejects_missing_text(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->from(route('admin.email-template.create'))
            ->post(route('admin.email-template.store'), [
                'name' => 'Chào mừng đăng ký',
                'code' => 'customer_register',
                'status' => 1,
                'submit' => 'save',
            ])
            ->assertRedirect(route('admin.email-template.create'))
            ->assertSessionHasErrors('text');

        $this->assertSame(0, EmailTemplate::query()->where('code', 'customer_register')->count());
    }

    public function test_store_rejects_duplicate_code(): void
    {
        $admin = $this->createAdministrator();

        EmailTemplate::query()->create([
            'name' => 'Existing',
            'code' => 'customer_register',
            'text' => '<p>Old</p>',
            'status' => 1,
            'sort' => 1,
        ]);

        $this->actingAs($admin, 'admin')
            ->from(route('admin.email-template.create'))
            ->post(route('admin.email-template.store'), [
                'name' => 'Duplicate',
                'code' => 'customer_register',
                'text' => '<p>New</p>',
                'status' => 1,
                'submit' => 'save',
            ])
            ->assertRedirect(route('admin.email-template.create'))
            ->assertSessionHasErrors('code');
    }
}
