<?php

namespace Tests\Feature;

use App\Models\Backend\Page;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Tests\TestCase;

class AdminPostCrudTest extends TestCase
{
    private function createAdmin(): User
    {
        $admin = User::create([
            'name' => 'Post Admin',
            'username' => 'post_admin_'.time(),
            'email' => 'post_admin_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    private function createPost(string $slug): Page
    {
        return Page::create([
            'name' => 'Test Post',
            'slug' => $slug,
            'type' => 'post',
            'status' => 1,
            'sort' => 1,
        ]);
    }

    public function test_guest_cannot_delete_post(): void
    {
        $post = $this->createPost('guest-delete-post-'.time());

        $this->delete(route('admin.post.destroy', $post->id))
            ->assertRedirect('/admin/login');

        $this->assertDatabaseHas('pages', ['id' => $post->id]);

        $post->delete();
    }

    public function test_admin_can_delete_post(): void
    {
        $admin = $this->createAdmin();
        $post = $this->createPost('admin-delete-post-'.time());

        $response = $this
            ->actingAs($admin, 'admin')
            ->delete(route('admin.post.destroy', $post->id));

        $response->assertRedirect(route('admin.post.index'));
        $response->assertSessionHas('success', 'Post deleted successfully.');
        $this->assertDatabaseMissing('pages', ['id' => $post->id]);

        $admin->delete();
    }

    public function test_admin_cannot_delete_page_record_via_post_destroy(): void
    {
        $admin = $this->createAdmin();
        $page = Page::create([
            'name' => 'Static Page',
            'slug' => 'static-page-'.time(),
            'type' => 'page',
            'status' => 1,
            'sort' => 1,
        ]);

        $this
            ->actingAs($admin, 'admin')
            ->delete(route('admin.post.destroy', $page->id))
            ->assertNotFound();

        $this->assertDatabaseHas('pages', ['id' => $page->id]);

        $page->delete();
        $admin->delete();
    }

    public function test_post_show_redirects_to_edit(): void
    {
        $admin = $this->createAdmin();
        $post = $this->createPost('post-show-'.time());

        $response = $this
            ->actingAs($admin, 'admin')
            ->get(route('admin.post.show', $post->id));

        $response->assertRedirect(route('admin.post.edit', $post->id));

        $post->delete();
        $admin->delete();
    }
}
