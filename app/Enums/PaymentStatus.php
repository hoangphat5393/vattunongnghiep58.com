<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case Unpaid = 0;     // Chưa thanh toán (default in DB is 0)
    case Paid = 1;       // Đã thanh toán
    case Pending = 2;    // Đang chờ
    case Refunded = 3;   // Hoàn tiền
    case Expired = 4;    // Hết hạn
    case Failed = 5;     // Thanh toán lỗi

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Chưa thanh toán',
            self::Paid => 'Đã thanh toán',
            self::Pending => 'Đang chờ',
            self::Refunded => 'Hoàn tiền',
            self::Expired => 'Hết hạn',
            self::Failed => 'Thanh toán lỗi',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::Unpaid => 'Processing',
            self::Paid => 'Wait for confirmation',
            self::Pending => 'Pending',
            self::Refunded => 'Refunded',
            self::Expired => 'Expired',
            self::Failed => 'Failed',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-secondary text-white',
            self::Paid => 'bg-success text-white',
            self::Pending => 'bg-warning text-dark',
            self::Refunded => 'bg-dark text-white',
            self::Expired => 'bg-muted text-white',
            self::Failed => 'bg-danger text-white',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function asArray(): array
    {
        $arr = [];
        foreach (self::cases() as $case) {
            $arr[$case->value] = $case->label();
        }

        return $arr;
    }
}
