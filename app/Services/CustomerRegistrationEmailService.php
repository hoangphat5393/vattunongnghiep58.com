<?php

namespace App\Services;

use App\Models\Frontend\EmailTemplate;
use App\Models\Frontend\User;
use App\Support\EmailTemplateCodes;
use Illuminate\Support\Facades\Mail;

class CustomerRegistrationEmailService
{
    /**
     * Mã template cũ (trước khi đổi sang new_register).
     */
    private const LEGACY_CUSTOMER_REGISTER = 'customer_register';

    /**
     * @var array<string, string>
     */
    private const PLACEHOLDER_KEYS = [
        '/\{\{\$fullname\}\}/' => 'fullname',
        '/\{\{\$name\}\}/' => 'name',
        '/\{\{\$email\}\}/' => 'email',
        '/\{\{\$phone\}\}/' => 'phone',
        '/\{\{\$url_web\}\}/' => 'url_web',
        '/\{\{\$company_name\}\}/' => 'company_name',
        '/\{\{\$hotline\}\}/' => 'hotline',
    ];

    public function sendRegistrationEmails(User $user): void
    {
        $replacements = $this->replacementValues($user);

        $this->sendFromTemplateCode(
            EmailTemplateCodes::NEW_REGISTER,
            $user->email,
            $replacements,
            'Đăng ký tài khoản thành công — '.setting_option('company_name', setting_option('webtitle', 'Vật Tư 58'))
        );

        $adminEmail = setting_option('email_admin') ?: setting_option('email');
        if (is_string($adminEmail) && $adminEmail !== '') {
            $this->sendFromTemplateCode(
                EmailTemplateCodes::CUSTOMER_REGISTER_ADMIN,
                $adminEmail,
                $replacements,
                'Thông báo tài khoản mới — '.request()->getHttpHost()
            );
        }
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private function sendFromTemplateCode(string $code, string $to, array $replacements, string $fallbackSubject): void
    {
        $template = $this->findPublishedTemplate($code);

        if ($template === null) {
            return;
        }

        $content = $this->renderTemplate($template->text ?? '', $replacements);
        if ($content === '') {
            return;
        }

        $fromEmail = setting_option('email_admin') ?: setting_option('email');
        $fromName = setting_option('company_name') ?: setting_option('webtitle', 'Vật Tư 58');
        $subject = $template->name ?: $fallbackSubject;

        Mail::send([], [], function ($message) use ($to, $fromEmail, $fromName, $subject, $content) {
            $message->from($fromEmail, $fromName)
                ->to($to)
                ->subject($subject)
                ->html(htmlspecialchars_decode($content));
        });
    }

    private function findPublishedTemplate(string $code): ?EmailTemplate
    {
        $template = EmailTemplate::findPublishedByCode($code);
        if ($template !== null) {
            return $template;
        }

        if ($code === EmailTemplateCodes::NEW_REGISTER) {
            return EmailTemplate::findPublishedByCode(self::LEGACY_CUSTOMER_REGISTER);
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    private function replacementValues(User $user): array
    {
        return [
            'fullname' => (string) ($user->fullname ?: $user->name ?: $user->email),
            'name' => (string) ($user->name ?: $user->fullname ?: ''),
            'email' => (string) $user->email,
            'phone' => (string) ($user->phone ?? ''),
            'url_web' => url('/'),
            'company_name' => (string) setting_option('company_name', setting_option('webtitle', 'Vật Tư 58')),
            'hotline' => (string) setting_option('hotline', setting_option('phone', '')),
        ];
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private function renderTemplate(string $text, array $replacements): string
    {
        if ($text === '') {
            return '';
        }

        $find = array_keys(self::PLACEHOLDER_KEYS);
        $values = [];
        foreach (self::PLACEHOLDER_KEYS as $key) {
            $values[] = $replacements[$key] ?? '';
        }

        return preg_replace($find, $values, htmlspecialchars_decode($text)) ?? '';
    }
}
