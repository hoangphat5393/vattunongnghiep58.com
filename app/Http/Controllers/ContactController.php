<?php

namespace App\Http\Controllers;

use App\Models\Frontend\Contact;
use App\Models\Frontend\EmailTemplate;
use App\Support\EmailTemplateCodes;
use App\Traits\LocalizeController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class ContactController extends Controller
{
    use LocalizeController;

    public $data = [
        'error' => false,
        'success' => false,
        'message' => '',
    ];

    public function getContact(Request $request, $type)
    {
        if ($type == 'request-contact') {
            $this->data['status'] = 'success';
            $this->data['type'] = $type;
            $this->data['url_current'] = $request->url_current;
            $this->data['product_title'] = $request->product_title;
            $this->data['view'] = view('theme.page.includes.get-contact-form', ['data' => $this->data])->render();
        }

        return response()->json($this->data);
    }

    public function submit(Request $request)
    {
        $detail = $request->input('contact');
        if (! is_array($detail) || empty($detail)) {
            $detail = $request->only(['name', 'email', 'address', 'phone', 'content']);
        }

        $shouldReturnJson = $request->expectsJson() || $request->wantsJson() || $request->ajax();

        // Validation rules
        $validator = Validator::make($detail, [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:9|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'content' => 'required|string|min:5|max:2000',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên!',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone.required' => 'Vui lòng cung cấp số điện thoại!',
            'phone.min' => 'Số điện thoại tối thiểu 9 số.',
            'email.email' => 'Địa chỉ email không đúng định dạng!',
            'content.required' => 'Vui lòng nhập nội dung lời nhắn!',
            'content.min' => 'Nội dung lời nhắn tối thiểu 5 ký tự.',
        ]);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();

            if ($shouldReturnJson) {
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Xác định loại liên hệ
        $contactType = ! empty($detail['type']) ? $detail['type'] : $request->input('type', 'contact');
        $contactType = in_array($contactType, ['agent', 'contact', 'consultation']) ? $contactType : 'contact';

        // Recaptcha Verification (if available)
        $score = 1.0;
        $recaptchaToken = $request->get('g-recaptcha-response');
        if (! empty($recaptchaToken) && class_exists('Lunaweb\RecaptchaV3\Facades\RecaptchaV3')) {
            try {
                $recaptchaAction = $contactType === 'agent' ? 'agent' : 'contact';
                $score = RecaptchaV3::verify($recaptchaToken, $recaptchaAction);
            } catch (\Throwable $e) {
                $score = 1.0;
            }
        }

        if ($score < 0.3) {
            $botMsg = 'Hệ thống phát hiện hành vi bất thường, vui lòng thử lại sau.';
            if ($shouldReturnJson) {
                return response()->json([
                    'status' => 'error',
                    'message' => $botMsg,
                ], 400);
            }

            return redirect()->back()->withErrors($botMsg)->withInput();
        }

        // Lưu thông tin liên hệ vào Database
        $data = [
            'name' => trim($detail['name'] ?? ''),
            'email' => trim($detail['email'] ?? ''),
            'address' => trim($detail['address'] ?? ''),
            'phone' => trim($detail['phone'] ?? ''),
            'content' => trim($detail['content'] ?? ''),
            'type' => $contactType,
            'status' => 0,
        ];

        $contact = Contact::create($data);
        $insert_id = $contact->id;
        Contact::where('id', $insert_id)->update(['sort' => $insert_id]);

        // Xử lý gửi Mail thông báo
        try {
            $mail_customer = EmailTemplate::findPublishedByCode(EmailTemplateCodes::CONTACT_ADMIN);
            $mail_content = $mail_customer?->text ?? '';

            if ($mail_content !== '') {
                $dataFind = [
                    '/\{\{\$name\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$address\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$content\}\}/',
                ];
                $mail_content = preg_replace($dataFind, $data, $mail_content);
            } else {
                $typeLabel = $contactType === 'agent' ? 'Đại lý' : 'Liên hệ';
                $mail_content = 'Loại: '.$typeLabel.'<br>'
                    .'Họ tên: '.e($data['name']).'<br>'
                    .'Email: '.e($data['email']).'<br>'
                    .'SĐT: '.e($data['phone']).'<br>'
                    .'Địa chỉ: '.e($data['address']).'<br>'
                    .'Nội dung: '.nl2br(e($data['content']));
            }

            $sub = setting_option('webtitle', 'Vật Tư Nông Nghiệp 58');
            $adminEmail = setting_option('email_admin', config('mail.from.address'));
            $from_mail = [$adminEmail ?: 'info@vattunongnghiep58.com', setting_option('webtitle', 'Vật Tư Nông Nghiệp 58')];
            $typeTitle = $contactType === 'agent' ? 'Đăng ký làm đại lý' : 'Đăng ký tư vấn';
            $subject = $sub.' - '.$typeTitle.' ('.date('Y-m-d H:i:s').')';

            if (! empty($data['email'])) {
                Mail::send([], [], function ($message) use ($data, $from_mail, $subject, $mail_content) {
                    $message->from($from_mail[0], $from_mail[1])
                        ->to($data['email'])
                        ->subject($subject)
                        ->html(htmlspecialchars_decode($mail_content));
                });
            }

            if (! empty($adminEmail)) {
                Mail::send([], [], function ($message) use ($from_mail, $adminEmail, $subject, $mail_content) {
                    $message->from($from_mail[0], $from_mail[1])
                        ->to($adminEmail)
                        ->subject($subject)
                        ->html(htmlspecialchars_decode($mail_content));
                });
            }
        } catch (\Throwable $e) {
            Log::error('Contact mail send error: '.$e->getMessage());
        }

        $redirectUrl = route('contact_completed');

        if ($shouldReturnJson) {
            return response()->json([
                'status' => 'success',
                'message' => 'Gửi thông tin liên hệ thành công!',
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->to($redirectUrl)->with('contact_name', $data['name']);
    }

    public function completed(Request $request): View
    {
        return view('frontend.page.contact-completed', [
            'seo' => [
                'seo_title' => 'Hoàn tất liên hệ - Vật Tư Nông Nghiệp 58',
                'seo_keyword' => 'lien he vat tu nong nghiep 58',
                'seo_description' => 'Hoàn tất gửi thông tin liên hệ',
                'seo_image' => get_image(setting_option('logo')),
            ],
        ]);
    }
}
