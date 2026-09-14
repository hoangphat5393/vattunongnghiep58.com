<?php

namespace Tests\Feature;

use Tests\TestCase;

class AiChatTest extends TestCase
{
    /**
     * Test homepage includes the AI chat widget.
     */
    public function test_homepage_renders_ai_chat_widget(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ai-chat-widget', false);
        $response->assertSee('Trợ Lý Nông Nghiệp 58', false);
    }

    /**
     * Test validation error when message is empty.
     */
    public function test_ai_chat_validates_required_message(): void
    {
        $response = $this->postJson('/ai-chat', [
            'message' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
            ]);
    }

    /**
     * Test resetting AI chat conversation session.
     */
    public function test_ai_chat_reset_clears_session(): void
    {
        $this->withSession([
            'ai_chat_history' => [
                ['role' => 'user', 'content' => 'Xin chào'],
                ['role' => 'assistant', 'content' => 'Chào bà con'],
            ],
        ]);

        $response = $this->postJson('/ai-chat/reset');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertEmpty(session('ai_chat_history'));
    }

    /**
     * Test live AI chat prompt via Gemini API.
     */
    public function test_ai_chat_sends_prompt_and_receives_reply(): void
    {
        if (empty(env('GEMINI_API_KEY'))) {
            $this->markTestSkipped('GEMINI_API_KEY is not configured in .env');
        }

        $response = $this->postJson('/ai-chat', [
            'message' => 'Chào chuyên gia, cho tôi hỏi đạo ôn lá lúa thì xịt thuốc gì?',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'reply',
                'history_count',
            ]);

        $this->assertEquals('success', $response->json('status'));
        $this->assertNotEmpty($response->json('reply'));
    }
}
