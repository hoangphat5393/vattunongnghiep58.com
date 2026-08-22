<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactFormTest extends TestCase
{
    public function test_contact_submit_validation_error(): void
    {
        $response = $this->postJson('/contact', [
            'contact' => [
                'name' => '',
                'phone' => '',
                'content' => '',
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'phone', 'content']);
    }

    public function test_contact_submit_success(): void
    {
        $response = $this->postJson('/contact', [
            'contact' => [
                'name' => 'Nguyễn Văn Test',
                'phone' => '0912345678',
                'email' => 'test@example.com',
                'address' => 'Hà Nội',
                'content' => 'Tôi muốn được tư vấn giá phân bón vật tư nông nghiệp.',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'Nguyễn Văn Test',
            'phone' => '0912345678',
        ]);
    }

    public function test_contact_completed_page_loads(): void
    {
        $response = $this->get('/contact-completed');
        $response->assertStatus(200);
    }
}
