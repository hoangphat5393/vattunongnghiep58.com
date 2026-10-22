<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 0;     // Chờ xác nhận (default in DB is 0)
    case Processing = 1;  // Đang xử lý / Đã tiếp nhận
    case Completed = 2;   // Hoàn thành
    case Canceled = 3;    // Đã hủy
    case Shipping = 4;    // Đang giao hàng

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ xác nhận',
            self::Processing => 'Đang xử lý',
            self::Completed => 'Hoàn thành',
            self::Canceled => 'Đã hủy',
            self::Shipping => 'Đang giao hàng',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::Pending => 'Wait for confirmation',
            self::Processing => 'Processing',
            self::Completed => 'Done',
            self::Canceled => 'Canceled',
            self::Shipping => 'Delivery in progress',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-warning text-dark',
            self::Processing => 'bg-info text-white',
            self::Completed => 'bg-success text-white',
            self::Canceled => 'bg-danger text-white',
            self::Shipping => 'bg-primary text-white',
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
