# SỔ TAY TÍNH NĂNG NÂNG CAO (ADVANCED FEATURES PLAYBOOK)

> **Mục đích tài liệu:** Lưu trữ chi tiết kiến trúc, điều kiện cần, quy trình triển khai từng bước và các kinh nghiệm thực chiến (nhược điểm & cách khắc phục) của các tính năng nâng cao trong hệ thống các dự án web Laravel.  
> **Khả năng tái sử dụng:** Dùng làm tài liệu chuẩn (Standard Operating Procedure - SOP) để nhân bản hoặc triển khai sang bất kỳ dự án Laravel nào (TMĐT, Dịch vụ, Salon, Nông nghiệp...) mà không cần tốn thời gian nghiên cứu lại từ đầu.

---

# MỤC 1: AI CHATBOT TƯ VẤN THÔNG MINH ĐA DỰ ÁN

_(Kiến trúc: Google Gemini API + Laravel AI SDK + Persistent State + Multi-tab Sync)_

---

## 1. MA TRẬN TRIỂN KHAI CÁC DỰ ÁN (PORTFOLIO IMPLEMENTATION MATRIX)

Hệ sinh thái hiện đã triển khai thành công Chatbot AI trên 3 dự án thực tế với 2 ngành nghề khác nhau:

| Tiêu Chí                | 🌾 vattunongnghiep58                                 | 🌾 3nong                                           | ✂️ salondungtokyo                                                  |
| :---------------------- | :--------------------------------------------------- | :------------------------------------------------- | :----------------------------------------------------------------- |
| **Domain áp dụng**      | `vattunongnghiep58.com`                              | `3nong.vn`                                         | `salondungtokyo.com`                                               |
| **Tên Trợ Lý AI**       | **Trợ Lý Nông Nghiệp 58**                            | **Trợ Lý 3 Nông**                                  | **Stylist AI - Dũng Tokyo**                                        |
| **Lĩnh Vực Chuyên Môn** | Sâu bệnh lúa & cây ăn trái, phân bón NPK, thuốc BVTV | Nông nghiệp miền Tây & Tây Nguyên, BVTV chính hãng | Tạo mẫu tóc nam/nữ, màu nhuộm tôn da, phục hồi, đặt lịch           |
| **Lớp Agent AI**        | `AgriculturalAdvisorAgent.php`                       | `AgriculturalAdvisorAgent.php`                     | `SalonAdvisorAgent.php`                                            |
| **Tone Màu Giao Diện**  | Xanh lá mạ + Trắng (`#2e7d32`)                       | Xanh lá mạ + Trắng (`#2e7d32`)                     | Đen huyền bí + Vàng Gold hoàng gia (`#d4af37`)                     |
| **Icon Nút Nổi**        | Mầm cây 🌱                                           | Mầm cây 🌱                                         | Cây kéo làm đẹp ✂️                                                 |
| **Nhãn Nút Nổi**        | `Tư vấn AI`                                          | `Tư vấn 3 Nông` / `Tư vấn AI`                      | `Tư vấn AI`                                                        |
| **Storage Key**         | `vt58_ai_chat_data`                                  | `3nong_ai_chat_data`                               | `salondungtokyo_ai_chat_data`                                      |
| **Vị Trí Nút Bấm**      | Góc dưới phải (`bottom: 24px; right: 24px`)          | Góc dưới phải (`bottom: 24px; right: 24px`)        | Góc dưới phải, **dưới cùng** cột CTA (`bottom: 20px; right: 18px`) |
| **Tự Động Hết Hạn**     | 2 giờ (TTL 2h khớp Laravel Session)                  | 2 giờ (TTL 2h khớp Laravel Session)                | 2 giờ (TTL 2h khớp Laravel Session)                                |
| **Đồng Bộ Đa Tab**      | Có (`window.storage` event)                          | Có (`window.storage` event)                        | Có (`window.storage` event)                                        |
| **Test Suite Tự Động**  | `tests/Feature/AiChatTest.php` (PASS 100%)           | `tests/Feature/AiChatTest.php` (PASS 100%)         | `tests/Feature/AiChatTest.php` (PASS 100%)                         |

---

## 2. CÁC YẾU TỐ CẦN THIẾT (PREREQUISITES)

Để triển khai được Chatbot AI hoạt động hoàn hảo, cần chuẩn bị đủ 4 nhóm yếu tố sau:

### 2.1. Môi trường & Thư viện (Packages)

- **PHP:** `^8.2` hoặc `^8.3`
- **Laravel Framework:** `v11.x`, `v12.x` hoặc `v13.x`
- **Package AI chính thức:** `laravel/ai` (phiên bản `^0.11` trở lên)
  ```bash
  composer require laravel/ai
  ```
- **Database Engine:** MySQL / MariaDB.

### 2.2. API Key & Nhà Cung Cấp AI (AI Provider)

- **Google Gemini API Key:** Lấy miễn phí tại [Google AI Studio](https://aistudio.google.com/).
  - Gói Free Tier: Miễn phí, phản hồi cực nhanh, ngữ cảnh tiếng Việt tự nhiên.
- Cấu hình trong `.env`:
  ```env
  AI_DEFAULT_PROVIDER=gemini
  GEMINI_API_KEY=AIzaSyC1gKX1vPMbJeWvNsStWMgAb5rC7hL69O0
  GEMINI_MODEL=gemini-3.5-flash
  ```

### 2.3. Cấu trúc tệp tin cần tạo trong mỗi dự án

- `config/ai.php`: Tệp cấu hình provider (sửa `'default' => env('AI_DEFAULT_PROVIDER', 'gemini')`).
- `app/Ai/Agents/...Agent.php`: Lớp định hình tính cách chuyên gia (Nông nghiệp hoặc Salon tóc).
- `app/Http/Controllers/AiChatController.php`: Controller điều hướng yêu cầu chat, validate, lưu Session 8 tin nhắn gần nhất, phản hồi JSON.
- `routes/web.php`: Các endpoint `POST /ai-chat` và `POST /ai-chat/reset`.
- `resources/views/frontend/components/ai-chat-widget.blade.php`: Widget giao diện nổi (CSS/JS scoped, localStorage, TTL 2h, đa tab).
- `tests/Feature/AiChatTest.php`: Bài kiểm thử tự động toàn diện.

---

## 3. BẢNG NHƯỢC ĐIỂM & KINH NGHIỆM THỰC CHIẾN (ISSUE & RESOLUTION MATRIX)

Bảng tổng hợp tất cả các lỗi thực tế đã phát sinh khi triển khai qua 3 dự án và giải pháp kỹ thuật triệt để:

| STT | Vấn đề / Nhược điểm thực tế                              | Phân tích nguyên nhân                                                                                                                                                      | Giải pháp kỹ thuật                                                                                                                                                                                 |    Trạng thái    |
| :-: | :------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :--------------: |
|  1  | **Chuyển trang bị mất nội dung chat**                    | Web Laravel là Multi-Page App (MPA), khi chuyển trang thì trình duyệt reload lại toàn bộ trang, biến JS bị reset.                                                          | Lưu toàn bộ tin nhắn vào `localStorage` của trình duyệt. Khi sang trang mới, script đọc lại và vẽ lại toàn bộ đoạn chat trong 0ms.                                                                 | [x] **Đã xử lý** |
|  2  | **Chuyển trang hộp chat bị thu nhỏ**                     | Trạng thái hiển thị mặc định ban đầu là đóng (`hidden`), khi chuyển trang bị đặt lại mặc định.                                                                             | Lưu cờ `isOpen: boolean` vào `localStorage`. Nếu trước đó khách đang mở, trang mới sẽ tự mở sẵn và cuộn xuống tin nhắn cuối.                                                                       | [x] **Đã xử lý** |
|  3  | **Lệch pha giữa máy khách và máy chủ (Session Expiry)**  | `localStorage` nằm mãi mãi trên máy khách, nhưng Session của Laravel hết hạn sau 120 phút. Nếu 3 ngày sau khách hỏi tiếp, máy chủ mất ngữ cảnh cũ khiến AI trả lời lạc đề. | Cài đặt cơ chế kiểm tra hạn sử dụng **TTL 2 giờ** (`STORAGE_TTL = 2 * 60 * 60 * 1000`). Nếu quá 2 tiếng không chat, client tự dọn sạch storage về câu chào ban đầu.                                | [x] **Đã xử lý** |
|  4  | **Lỗi CSRF Token (419 Page Expired)**                    | Khách mở tab chat lâu không chuyển trang khiến CSRF Token bị hết hạn, hoặc request AJAX gửi thiếu header CSRF.                                                             | Đính kèm `X-CSRF-TOKEN` lấy từ thẻ `<meta name="csrf-token">` trong mọi request fetch AJAX, bắt lỗi mạng để báo tin nhắn thân thiện thay vì sập giao diện.                                         | [x] **Đã xử lý** |
|  5  | **Xung đột khi khách mở nhiều Tab cùng lúc**             | Khách mở 3 tab sản phẩm khác nhau và chat ở 1 tab, các tab khác không cập nhật khiến dữ liệu ghi đè lộn xộn.                                                               | Sử dụng sự kiện `window.addEventListener('storage', ...)` để khi Tab A có tin nhắn mới, Tab B và C tự động render theo thời gian thực.                                                             | [x] **Đã xử lý** |
|  6  | **Khách muốn làm mới cuộc trò chuyện**                   | Khách muốn đổi sang hỏi về chủ đề khác mà không muốn bị AI nhớ nhầm thông tin của chủ đề trước đó.                                                                         | Bổ sung nút **Reset (icon xoay tròn)** trên thanh header: Vừa xóa sạch `localStorage`, vừa gọi API `POST /ai-chat/reset` để xóa Session trên server.                                               | [x] **Đã xử lý** |
|  7  | **Xung đột vị trí đè icon (như ở Salon Dũng Tokyo)**     | Trang web đã có sẵn cụm Floating CTA (Zalo, Hotline, Đặt lịch) ở góc dưới bên phải, khiến nút AI đặt mặc định bị đè trực tiếp lên.                                         | Nâng cụm nút liên hệ cũ lên `bottom: 92px`, đặt nút Chatbot AI ở vị trí **dưới cùng** (`bottom: 20px; right: 18px`). Toàn bộ 4 icon tạo thành 1 cột thẳng tắp, cách đều nhau cực kỳ chuyên nghiệp. | [x] **Đã xử lý** |
|  8  | **Nhãn chữ badge bị cắt cụt do tràn mép phải**           | Tên nhãn dài (ví dụ: _Tư vấn làm tóc_) đặt căn lề lệch phải bị mép màn hình cắt mất chữ.                                                                                   | Đổi nhãn ngắn gọn thành **`Tư vấn AI`** (hoặc `Stylist AI`) và căn lề neo chuẩn không chạm mép màn hình.                                                                                           | [x] **Đã xử lý** |
|  9  | **Nhãn chữ badge bị lệch không nằm chính giữa nút tròn** | Dùng căn lề `right` cố định khiến badge chữ lệch hẳn về một bên so với tâm nút tròn.                                                                                       | Căn giữa tuyệt đối bằng `left: 50%; transform: translateX(-50%)`, đồng thời đưa `translateX(-50%)` vào cả `@keyframes aiBadgeFloat` để nhãn luôn nằm chính giữa tâm nút 100%.                      | [x] **Đã xử lý** |
| 10  | **Nhãn chữ đè lên nút đóng (icon X) khi mở chat**        | Khi mở khung chat, nút tròn đổi thành icon X màu vàng nhưng nhãn chữ vẫn nổi đè lên trên.                                                                                  | Thêm quy tắc CSS `#ai-chat-launcher.ai-active .ai-badge-pulse { display: none; }` để khi mở chat nhãn tự động ẩn, nút X tròn hiển thị sạch sẽ.                                                     | [x] **Đã xử lý** |
| 11  | **Lỗi ký tự UTF-8 BOM khi ghi file bằng script**         | Script PowerShell mặc định ghi kèm UTF-8 BOM (`\uFEFF`), làm hỏng namespace declaration của PHP (`Namespace declaration statement has to be the very first statement`).    | Luôn ghi file mã nguồn PHP bằng bảng mã UTF-8 No BOM: `[System.Text.UTF8Encoding]::new($false)`.                                                                                                   | [x] **Đã xử lý** |
| 12  | **Đồng bộ xuyên thiết bị (Cross-device)**                | Khách chat trên máy tính nhưng mở điện thoại ra không xem lại được do `localStorage` chỉ lưu cục bộ trên máy đó.                                                           | **Giải pháp tương lai:** Cần khách hàng đăng nhập tài khoản và lưu lịch sử vào Database theo `user_id`. (Khách vãng lai hiện tại lưu `localStorage` là tối ưu nhất).                               | [ ] _Dự kiến v2_ |
| 13  | **Context-Aware (AI tự biết trang/sản phẩm đang xem)**   | Khách đứng ở trang Hạt giống Ngô hoặc Dịch vụ Uốn tóc nhưng phải gõ lại tên sản phẩm thì AI mới biết.                                                                      | **Giải pháp tương lai:** Đọc thẻ meta hoặc URL hiện tại để nhúng ngữ cảnh ngầm vào prompt gửi cho AI.                                                                                              | [ ] _Dự kiến v2_ |

---

## 4. QUY TRÌNH TRIỂN KHAI TỪNG BƯỚC (STEP-BY-STEP WORKFLOW)

Khi mang sang dự án mới, chỉ cần thực hiện đúng theo 6 bước chuẩn mực sau:

### Bước 1: Cài đặt Package & Xuất file cấu hình

```bash
# 1. Cài đặt package Laravel AI
composer require laravel/ai

# 2. Xuất file cấu hình config/ai.php
php artisan vendor:publish --tag=ai-config
```

### Bước 2: Khai báo Biến Môi Trường (.env) & Cấu hình Provider

Trong file `config/ai.php`, sửa provider mặc định:

```php
'default' => env('AI_DEFAULT_PROVIDER', 'gemini'),
```

Thêm vào cuối file `.env`:

```env
AI_DEFAULT_PROVIDER=gemini
GEMINI_API_KEY=your_actual_gemini_api_key_here
GEMINI_MODEL=gemini-3.5-flash
```

Sau đó xóa bộ nhớ đệm cấu hình:

```bash
php artisan config:clear
```

---

### Bước 3: Tạo Lớp AI Agent Định Hình Ngành Nghề

#### Mẫu A: Ngành Nông Nghiệp / Cây Trồng (`AgriculturalAdvisorAgent.php`)

_(Áp dụng cho: `vattunongnghiep58`, `3nong`)_

```php
<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;
use Stringable;

class AgriculturalAdvisorAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(public array $history = []) {}

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Bạn là "Trợ Lý Nông Nghiệp" - chuyên gia tư vấn kỹ thuật trực tuyến thân thiện, am hiểu và tận tâm.
Nhiệm vụ: Tư vấn sâu bệnh hại (rầy nâu, đạo ôn, thán thư...), quy trình bón phân NPK, hữu cơ, thuốc BVTV theo nguyên tắc 4 đúng.
Phong cách: Xưng "em/tôi" với "bà con/quý khách", dân dã, dễ hiểu, gạch đầu dòng rõ ràng.
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

    public function tools(): iterable { return []; }
}
```

#### Mẫu B: Ngành Tạo Mẫu Tóc & Làm Đẹp (`SalonAdvisorAgent.php`)

_(Áp dụng cho: `salondungtokyo`)_

```php
<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;
use Stringable;

class SalonAdvisorAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(public array $history = []) {}

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Bạn là "Stylist AI - Salon Dũng Tokyo" - chuyên gia tạo mẫu tóc và chăm sóc sắc đẹp cao cấp, tận tâm và sành điệu.
Nhiệm vụ:
1. Tư vấn kiểu tóc nam/nữ theo khuôn mặt (Layer, Uốn sóng lơi, Hippie, Mullet, Side part, Bob...).
2. Tư vấn màu nhuộm thời thượng tôn da (nâu trà sữa, nâu khói, balayage, không tẩy...).
3. Chăm sóc phục hồi tóc hư tổn (Keratin, phủ lụa Collagen).
4. Hướng dẫn đặt lịch hẹn (Booking) và ước lượng chi phí dịch vụ.
Phong cách: Lịch thiệp, sành điệu, xưng "em" với "chị/anh/quý khách", gạch đầu dòng rõ ràng.
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

    public function tools(): iterable { return []; }
}
```

---

### Bước 4: Tạo Controller Xử Lý API (`app/Http/Controllers/AiChatController.php`)

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AiChatController extends Controller
{
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
            // Thay đổi lớp Agent phù hợp với dự án
            $agent = new \App\Ai\Agents\AgriculturalAdvisorAgent($history);
            // hoặc: $agent = new \App\Ai\Agents\SalonAdvisorAgent($history);

            $replyText = (string) $agent->prompt($userMessage);

            $history[] = ['role' => 'user', 'content' => $userMessage];
            $history[] = ['role' => 'assistant', 'content' => $replyText];
            Session::put('ai_chat_history', $history);

            return response()->json(['status' => 'success', 'reply' => $replyText]);
        } catch (\Throwable $e) {
            Log::error('AI Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Hệ thống bận, vui lòng thử lại sau!'], 500);
        }
    }

    public function reset(): JsonResponse
    {
        Session::forget('ai_chat_history');
        return response()->json(['status' => 'success', 'message' => 'Đã làm mới cuộc trò chuyện.']);
    }
}
```

---

### Bước 5: Đăng Ký Routes (`routes/web.php`)

Đặt trước các route wildcard `{slug}` hoặc `{slug}.html`:

```php
Route::post('ai-chat', [App\Http\Controllers\AiChatController::class, 'chat'])->name('ai.chat');
Route::post('ai-chat/reset', [App\Http\Controllers\AiChatController::class, 'reset'])->name('ai.chat.reset');
```

---

### Bước 6: Nhúng Giao Diện Widget & Tinh Chỉnh Bố Cục Chống Đè Icon

Nhúng vào file bố cục chính `resources/views/frontend/layouts/master.blade.php`:

```blade
<!-- AI Chatbot Floating Widget -->
@include('frontend.components.ai-chat-widget')
```

**Kỹ thuật CSS chống đè icon và căn giữa nhãn chuẩn:**

1. **Căn giữa nhãn chữ trên đỉnh nút tròn:**

   ```css
   .ai-badge-pulse {
     position: absolute;
     top: -10px;
     left: 50%;
     transform: translateX(-50%);
     white-space: nowrap;
     animation: aiBadgeFloat 3s infinite ease-in-out;
     pointer-events: none;
   }

   @keyframes aiBadgeFloat {
     0%,
     100% {
       transform: translateX(-50%) translateY(0);
     }
     50% {
       transform: translateX(-50%) translateY(-3px);
     }
   }
   ```

2. **Ẩn nhãn chữ khi khung chat đang mở (nút chuyển sang dấu X):**
   ```css
   #ai-chat-launcher.ai-active .ai-badge-pulse {
     display: none;
   }
   ```
3. **Phối hợp với cụm nút Hotline/Zalo có sẵn:**
   - Đặt cụm nút Hotline/Zalo ở `bottom: 92px; right: 20px;`.
   - Đặt nút AI Chatbot ở `bottom: 20px; right: 18px;`.
   - Tạo thành 1 hàng dọc 4 icon thẳng tắp từ trên xuống dưới.

---

## 5. KIỂM CHỨNG & TIÊU CHUẨN ĐÁNH GIÁ (VERIFICATION CHECKLIST)

Mỗi lần triển khai sang dự án mới, cần thực hiện đủ bộ 3 kiểm thử sau:

```bash
# 1. Chạy bài kiểm tra tự động Feature Test
php artisan test --compact --filter=AiChatTest

# 2. Định dạng code theo chuẩn Pint
vendor/bin/pint --dirty --format agent

# 3. Kiểm tra thực tế trên trình duyệt:
# - Gửi câu hỏi -> AI trả lời thành công
# - Chuyển trang bất kỳ -> Toàn bộ đoạn chat và khung chat vẫn giữ nguyên (0ms)
# - Kiểm tra góc màn hình -> Các icon xếp ngay ngắn, không bị đè lên nhau
# - Bấm nút làm mới -> Reset về câu chào ban đầu
```

---

_Tài liệu được cập nhật toàn diện vào ngày 15/09/2026 - Áp dụng đồng bộ cho các dự án: Vật Tư Nông Nghiệp 58, 3 Nông (3nong.vn) và Salon Dũng Tokyo (salondungtokyo.com)._
