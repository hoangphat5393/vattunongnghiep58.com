<?php

namespace App\Http\Controllers;

use App\Ai\Agents\AgriculturalAdvisorAgent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AiChatController extends Controller
{
    /**
     * Send message to Agricultural Advisor Agent.
     */
    public function chat(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:2|max:1000',
        ], [
            'message.required' => 'Vui lòng nhập câu hỏi của bạn!',
            'message.min' => 'Câu hỏi quá ngắn, vui lòng nhập ít nhất 2 ký tự.',
            'message.max' => 'Câu hỏi không được vượt quá 1000 ký tự.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $userMessage = trim($request->input('message'));

        // Lấy lịch sử hội thoại gần nhất trong Session (tối đa 8 tin nhắn gần nhất)
        $history = Session::get('ai_chat_history', []);
        if (! is_array($history)) {
            $history = [];
        }

        // Cắt gọn lịch sử nếu dài quá
        if (count($history) > 8) {
            $history = array_slice($history, -8);
        }

        try {
            $agent = new AgriculturalAdvisorAgent($history);
            $response = $agent->prompt($userMessage);
            $replyText = (string) $response;

            // Cập nhật lại lịch sử hội thoại
            $history[] = ['role' => 'user', 'content' => $userMessage];
            $history[] = ['role' => 'assistant', 'content' => $replyText];

            Session::put('ai_chat_history', $history);

            return response()->json([
                'status' => 'success',
                'reply' => $replyText,
                'history_count' => count($history),
            ]);
        } catch (\Throwable $e) {
            Log::error('AI Chat Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Hệ thống AI đang bận hoặc quá tải, vui lòng thử lại sau giây lát!',
                'error_detail' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Reset chat conversation history.
     */
    public function reset(): JsonResponse
    {
        Session::forget('ai_chat_history');

        return response()->json([
            'status' => 'success',
            'message' => 'Đã làm mới cuộc trò chuyện thành công.',
        ]);
    }
}
