<?php

namespace App\Support;

final class EmailTemplateCodes
{
    public const NEW_REGISTER = 'new_register';

    /** @deprecated Dùng {@see self::NEW_REGISTER} — giữ alias để code cũ vẫn compile */
    public const CUSTOMER_REGISTER = 'new_register';

    public const CUSTOMER_REGISTER_ADMIN = 'customer_register_admin';

    public const CONTACT_ADMIN = 'contact_admin';

    public const ORDER_TO_USER = 'order_to_user';

    public const ORDER_TO_ADMIN = 'order_to_admin';

    public const REQUEST_PAYMENT_SUCCESS = 'request_payment_success';

    /**
     * Mã code hệ thống đang dùng — tham khảo khi tạo template trong admin.
     *
     * @return array<string, array{label: string, placeholders: list<string>}>
     */
    public static function registry(): array
    {
        return [
            self::NEW_REGISTER => [
                'label' => 'Đăng ký tài khoản — gửi cho khách (code: new_register)',
                'placeholders' => [
                    '{{$fullname}}',
                    '{{$name}}',
                    '{{$email}}',
                    '{{$phone}}',
                    '{{$url_web}}',
                    '{{$company_name}}',
                    '{{$hotline}}',
                ],
            ],
            self::CUSTOMER_REGISTER_ADMIN => [
                'label' => 'Đăng ký tài khoản — thông báo admin',
                'placeholders' => [
                    '{{$fullname}}',
                    '{{$name}}',
                    '{{$email}}',
                    '{{$phone}}',
                    '{{$url_web}}',
                    '{{$company_name}}',
                    '{{$hotline}}',
                ],
            ],
            self::CONTACT_ADMIN => [
                'label' => 'Form liên hệ — thông báo admin',
                'placeholders' => [
                    '{{$name}}',
                    '{{$email}}',
                    '{{$address}}',
                    '{{$phone}}',
                    '{{$content}}',
                ],
            ],
            self::ORDER_TO_USER => [
                'label' => 'Đặt hàng — gửi cho khách',
                'placeholders' => [
                    '{{$orderID}}',
                    '{{$toname}}',
                    '{{$email}}',
                    '{{$phone}}',
                    '{{$address}}',
                    '{{$comment}}',
                    '{{$subtotal}}',
                    '{{$total}}',
                    '{{$orderDetail}}',
                    '{{$payment_method}}',
                ],
            ],
            self::ORDER_TO_ADMIN => [
                'label' => 'Đặt hàng — thông báo admin',
                'placeholders' => [
                    '{{$orderID}}',
                    '{{$toname}}',
                    '{{$email}}',
                    '{{$phone}}',
                    '{{$address}}',
                    '{{$comment}}',
                    '{{$subtotal}}',
                    '{{$total}}',
                    '{{$orderDetail}}',
                    '{{$payment_method}}',
                ],
            ],
            self::REQUEST_PAYMENT_SUCCESS => [
                'label' => 'Yêu cầu thanh toán thành công',
                'placeholders' => [
                    '{{$orderID}}',
                    '{{$orderID_link}}',
                    '{{$toname}}',
                    '{{$email}}',
                    '{{$phone}}',
                    '{{$comment}}',
                ],
            ],
        ];
    }

    public static function normalize(string $code): string
    {
        $code = strtolower(trim($code));
        $code = preg_replace('/[^a-z0-9_]+/', '_', $code) ?? '';
        $code = trim($code, '_');

        return $code;
    }
}
