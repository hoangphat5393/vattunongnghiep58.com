<?php

namespace App\Http\Controllers;

use App\Models\Frontend\Contact;
use App\Models\Frontend\EmailTemplate;
use App\Support\EmailTemplateCodes;
use App\Traits\LocalizeController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $detail = $request->input('contact', []);
        $score = RecaptchaV3::verify($request->get('g-recaptcha-response'), 'contact');
        $shouldReturnJson = $request->expectsJson() || $request->wantsJson() || $request->ajax();

        if (! is_array($detail) || empty($detail)) {
            $this->data['status'] = 'error';
            $this->data['message'] = 'invalid contact payload';

            if ($shouldReturnJson) {
                return response()->json($this->data);
            }

            return redirect()->back()->withErrors($this->data['message'])->withInput();
        }

        if ($score > 0.7) {
            $mail_customer = EmailTemplate::findPublishedByCode(EmailTemplateCodes::CONTACT_ADMIN);
            $mail_content = $mail_customer?->text ?? '';

            $data = [
                'name' => $detail['name'] ?? '',
                'email' => $detail['email'] ?? '',
                'address' => $detail['address'] ?? '',
                'phone' => $detail['phone'] ?? '',
                'content' => $detail['content'] ?? '',
            ];

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
                $mail_content = 'Họ tên: '.e($data['name']).'<br>'
                    .'Email: '.e($data['email']).'<br>'
                    .'SĐT: '.e($data['phone']).'<br>'
                    .'Địa chỉ: '.e($data['address']).'<br>'
                    .'Nội dung: '.nl2br(e($data['content']));
            }

            $data['type'] = 'contact';
            $respons = Contact::create($data);
            $insert_id = $respons->id;

            Contact::where('id', $insert_id)->update(['sort' => $insert_id]);

            $sub = setting_option('webtitle');
            $from_mail = [setting_option('email_admin'), setting_option('webtitle') ?? ''];
            $subject = $sub.'Đăng ký tư vấn'.' ('.date('Y-m-d H:i:s').')';

            Mail::send([], [], function ($message) use ($data, $from_mail, $subject, $mail_content) {
                $message->from($from_mail[0])
                    ->to($data['email'])
                    ->subject($subject)
                    ->html(htmlspecialchars_decode($mail_content));
            });

            $sendToAdmin = setting_option('email_admin');
            Mail::send([], [], function ($message) use ($from_mail, $sendToAdmin, $subject, $mail_content) {
                $message->from($from_mail[0])
                    ->to($sendToAdmin)
                    ->subject($subject)
                    ->html(htmlspecialchars_decode($mail_content));
            });

            $redirectUrl = route('contact_completed');

            if ($shouldReturnJson) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'redirect' => $redirectUrl,
                ]);
            }

            return redirect()->to($redirectUrl)->with('contact_name', $detail['name'] ?? '');
        }

        $this->data['status'] = 'error';
        $this->data['message'] = $score > 0.3 ? 'require additional email verification' : 'You are most likely a bot';

        if ($shouldReturnJson) {
            return response()->json($this->data);
        }

        return redirect()->back()->withErrors($this->data['message'])->withInput();
    }

    public function completed(Request $request): View
    {
        return view('frontend.page.contact-completed', [
            'seo' => [
                'seo_title' => 'Hoàn tất liên hệ',
            ],
        ]);
    }
}
