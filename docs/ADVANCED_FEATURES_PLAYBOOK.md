# SỔ TAY TÍNH NĂNG NÂNG CAO (ADVANCED FEATURES PLAYBOOK)

> **Mục đích tài liệu:** Lưu trữ chi tiết kiến trúc, điều kiện cần, quy trình triển khai từng bước và các kinh nghiệm thực chiến (nhược điểm & cách khắc phục) của các tính năng nâng cao trong dự án.  
> **Khả năng tái sử dụng:** Dùng làm tài liệu chuẩn (Standard Operating Procedure - SOP) để nhân bản hoặc triển khai sang các dự án Laravel khác (ví dụ: `3nong.vn`, v.v.) mà không cần tốn thời gian nghiên cứu lại từ đầu.

---

# MỤC 1: AI CHATBOT TƯ VẤN NÔNG NGHIỆP THÔNG MINH

_(Áp dụng kiến trúc: Google Gemini API + Laravel AI SDK + Persistent State đa tab)_

---

## 1. CÁC YẾU TỐ CẦN THIẾT (PREREQUISITES)

Để triển khai được Chatbot AI hoạt động hoàn hảo, cần chuẩn bị đủ 4 nhóm yếu tố sau:

### 1.1. Môi trường & Thư viện (Packages)

- **PHP:** `^8.2` hoặc `^8.3`
- **Laravel Framework:** `v11.x`, `v12.x` hoặc `v13.x`
- **Package AI chính thức:** `laravel/ai` (phiên bản `^0.11` trở lên)
  ```bash
  composer require laravel/ai
  ```
- **Database Engine:** MySQL / MariaDB (hỗ trợ tạo bảng lưu lịch sử nếu cần).

### 1.2. API Key & Nhà Cung Cấp AI (AI Provider)

- **Google Gemini API Key:** Lấy miễn phí tại [Google AI Studio](https://aistudio.google.com/).
  - Gói Free Tier: Miễn phí, phản hồi cực nhanh, hỗ trợ ngữ cảnh tiếng Việt rất tự nhiên.
- Cấu hình trong `.env`:
  ```env
  AI_DEFAULT_PROVIDER=gemini
  GEMINI_API_KEY=AIzaSyC...
  ```

### 1.3. Cấu trúc tệp tin trong mã nguồn

- `config/ai.php`: Tệp cấu hình provider và model.
- Migration `agent_conversations`: Bảng lưu lịch sử hội thoại chuẩn của package.
- `app/Ai/Agents/AgriculturalAdvisorAgent.php`: Lớp định hình tính cách chuyên gia, prompt hướng dẫn và nạp lịch sử.
- `app/Http/Controllers/AiChatController.php`: Controller điều hướng yêu cầu chat, validate, lưu Session, phản hồi JSON.
- `routes/web.php`: Các endpoint `POST /ai-chat` và `POST /ai-chat/reset`.
- `resources/views/frontend/components/ai-chat-widget.blade.php`: Widget giao diện nổi (Floating button, cửa sổ chat, markdown, localStorage, đa tab).

---

## 2. NHƯỢC ĐIỂM & CÁCH KHẮC PHỤC (ISSUE & RESOLUTION MATRIX)

Bảng tổng hợp các vấn đề thực tế phát sinh khi vận hành Chatbot trên web và trạng thái xử lý:

| STT | Vấn đề / Nhược điểm thực tế                             | Phân tích nguyên nhân                                                                                                                                                      | Giải pháp kỹ thuật                                                                                                                                                   |    Trạng thái    |
| :-: | :------------------------------------------------------ | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :--------------: |
|  1  | **Chuyển trang bị mất nội dung chat**                   | Web Laravel là Multi-Page App (MPA), khi chuyển trang thì trình duyệt tải lại từ đầu (reload DOM), biến JS bị reset.                                                       | Lưu toàn bộ tin nhắn vào `localStorage` của trình duyệt. Khi sang trang mới, script đọc lại và vẽ lại toàn bộ đoạn chat trong 0ms.                                   | [x] **Đã xử lý** |
|  2  | **Chuyển trang hộp chat bị thu nhỏ**                    | Trạng thái hiển thị giao diện mặc định ban đầu là đóng (`hidden`), khi chuyển trang bị đặt lại mặc định.                                                                   | Lưu cờ `isOpen: boolean` vào `localStorage`. Nếu trước đó khách đang mở, trang mới sẽ tự mở sẵn và cuộn xuống tin nhắn cuối.                                         | [x] **Đã xử lý** |
|  3  | **Lệch pha giữa máy khách và máy chủ (Session Expiry)** | `localStorage` nằm mãi mãi trên máy khách, nhưng Session của Laravel hết hạn sau 120 phút. Nếu 3 ngày sau khách hỏi tiếp, máy chủ mất ngữ cảnh cũ khiến AI trả lời lạc đề. | Cài đặt cơ chế kiểm tra hạn sử dụng **TTL 2 giờ** (`STORAGE_TTL = 2 * 60 * 60 * 1000`). Nếu quá 2 tiếng không chat, client tự dọn sạch storage về câu chào ban đầu.  | [x] **Đã xử lý** |
|  4  | **Lỗi CSRF Token (419 Page Expired)**                   | Khách mở tab chat lâu không chuyển trang khiến CSRF Token bị hết hạn, hoặc request AJAX gửi thiếu header CSRF.                                                             | Đính kèm `X-CSRF-TOKEN` lấy từ thẻ `<meta name="csrf-token">` trong mọi request fetch AJAX, bắt lỗi mạng để báo tin nhắn thân thiện thay vì sập giao diện.           | [x] **Đã xử lý** |
|  5  | **Xung đột khi khách mở nhiều Tab cùng lúc**            | Khách mở 3 tab sản phẩm khác nhau và chat ở 1 tab, các tab khác không cập nhật khiến dữ liệu ghi đè lộn xộn.                                                               | Sử dụng sự kiện `window.addEventListener('storage', ...)` để khi Tab A có tin nhắn mới, Tab B và C tự động render theo thời gian thực.                               | [x] **Đã xử lý** |
|  6  | **Khách muốn làm mới cuộc trò chuyện**                  | Khách muốn đổi sang hỏi về cây trồng khác mà không muốn bị AI nhớ nhầm thông tin của cây trồng trước đó.                                                                   | Bổ sung nút **Reset (icon xoay tròn)** trên thanh header: Vừa xóa sạch `localStorage`, vừa gọi API `POST /ai-chat/reset` để xóa Session trên server.                 | [x] **Đã xử lý** |
|  7  | **Đồng bộ xuyên thiết bị (Cross-device)**               | Khách chat trên máy tính nhưng mở điện thoại ra không xem lại được do `localStorage` chỉ lưu cục bộ trên máy đó.                                                           | **Giải pháp tương lai:** Cần khách hàng đăng nhập tài khoản và lưu lịch sử vào Database theo `user_id`. (Khách vãng lai hiện tại lưu `localStorage` là tối ưu nhất). | [ ] _Dự kiến v2_ |
|  8  | **Context-Aware (AI tự biết sản phẩm đang xem)**        | Khách đứng ở trang Hạt giống Ngô nhưng phải gõ lại tên sản phẩm thì AI mới biết.                                                                                           | **Giải pháp tương lai:** Đọc thẻ meta sản phẩm hoặc URL hiện tại để nhúng ngữ cảnh ẩn vào câu hỏi gửi cho AI.                                                        | [ ] _Dự kiến v2_ |

---

## 3. QUY TRÌNH TRIỂN KHAI TỪNG BƯỚC (STEP-BY-STEP WORKFLOW)

Khi mang sang dự án mới, chỉ cần thực hiện đúng theo 6 bước chuẩn mực sau:

### Bước 1: Cài đặt Package & Xuất file cấu hình

```bash
# 1. Cài đặt package Laravel AI
composer require laravel/ai

# 2. Xuất file cấu hình config/ai.php
php artisan vendor:publish --tag=ai-config

# 3. Xuất và chạy migration bảng lịch sử hội thoại
php artisan vendor:publish --tag=ai-migrations
php artisan migrate
```

### Bước 2: Khai báo Biến Môi Trường (.env)

Thêm vào cuối file `.env`:

```env
AI_DEFAULT_PROVIDER=gemini
GEMINI_API_KEY=your_actual_gemini_api_key_here
```

Sau đó chạy xóa bộ nhớ đệm:

```bash
php artisan config:clear
```

### Bước 3: Tạo AI Agent Kỹ Thuật (`app/Ai/Agents/AgriculturalAdvisorAgent.php`)

Tạo lớp Agent thực thi `Agent`, `Conversational`:

```php
<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;
use Stringable;

class AgriculturalAdvisorAgent implements Agent, Conversational
{
    use Promptable;

    /**
     * @param array<int, array{role: string, content: string}> $history
     */
    public function __construct(public array $history = []) {}

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Bạn là chuyên gia tư vấn kỹ thuật nông nghiệp trực tuyến thân thiện, am hiểu và tận tâm.
Nhiệm vụ: Tư vấn sâu bệnh hại, quy trình bón phân NPK, hữu cơ, thuốc BVTV theo nguyên tắc 4 đúng.
Phong cách: Xưng hô "tôi/em" với "bà con/quý khách", dễ hiểu, gạch đầu dòng rõ ràng.
PROMPT;
    }

    public function messages(): iterable
    {
        $messages = [];
        foreach ($this->history as $msg) {
            if (($msg['role'] ?? '') === 'user') {
                $messages[] = new UserMessage($msg['content'] ?? '');
            } elseif (($msg['role'] ?? '') === 'assistant') {
                $messages[] = new AssistantMessage($msg['content'] ?? '');
            }
        }
        return $messages;
    }
}
```

### Bước 4: Tạo Controller Xử Lý API (`app/Http/Controllers/AiChatController.php`)

Chịu trách nhiệm validate, quản lý Session đệm 8 tin nhắn gần nhất và gọi Agent:

```php
public function chat(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'message' => 'required|string|min:2|max:1000',
    ]);
    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }

    $userMessage = trim($request->input('message'));
    $history = Session::get('ai_chat_history', []);
    if (count($history) > 8) {
        $history = array_slice($history, -8);
    }

    try {
        $agent = new AgriculturalAdvisorAgent($history);
        $replyText = (string) $agent->prompt($userMessage);

        $history[] = ['role' => 'user', 'content' => $userMessage];
        $history[] = ['role' => 'assistant', 'content' => $replyText];
        Session::put('ai_chat_history', $history);

        return response()->json(['status' => 'success', 'reply' => $replyText]);
    } catch (\Throwable $e) {
        Log::error('AI Error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Hệ thống bận, vui lòng thử lại!'], 500);
    }
}

public function reset(): JsonResponse
{
    Session::forget('ai_chat_history');
    return response()->json(['status' => 'success']);
}
```

### Bước 5: Đăng Ký Route (`routes/web.php`)

```php
use App\Http\Controllers\AiChatController;

Route::post('ai-chat', [AiChatController::class, 'chat'])->name('ai.chat');
Route::post('ai-chat/reset', [AiChatController::class, 'reset'])->name('ai.chat.reset');
```

### Bước 6: Nhúng Giao Diện Widget (`ai-chat-widget.blade.php`)

Nhúng `@include('frontend.components.ai-chat-widget')` vào file bố cục chính `master.blade.php`.
Trong widget:

- Sử dụng `STORAGE_KEY = 'vt58_ai_chat_data'` và `STORAGE_TTL = 2 * 60 * 60 * 1000`.
- Tự động gọi `loadChatState()` khi trang vừa sẵn sàng để phục hồi tin nhắn và mở lại khung chat.
- Lắng nghe `window.addEventListener('storage')` để đồng bộ đa tab.

---

## 4. KIỂM CHỨNG & TIÊU CHUẨN ĐÁNH GIÁ (VERIFICATION CHECKLIST)

Mỗi lần nhân bản tính năng này sang dự án mới, hãy chạy các lệnh kiểm thử sau:

```bash
# 1. Chạy bài kiểm tra tự động Feature Test
php artisan test --compact --filter=AiChatTest

# 2. Định dạng code theo chuẩn Pint
vendor/bin/pint --dirty --format agent

# 3. Kiểm tra thực tế trên trình duyệt:
# - Gửi câu hỏi -> AI trả lời thành công
# - Chuyển trang bất kỳ -> Toàn bộ đoạn chat và khung chat vẫn giữ nguyên
# - Bấm nút làm mới -> Reset về câu chào ban đầu
```

---

_Tài liệu được cập nhật lần cuối vào ngày 14/09/2026 - Dự án Vật Tư Nông Nghiệp 58._
