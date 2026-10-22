<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use PHPUnit\Framework\TestCase;

class OrderEnumsTest extends TestCase
{
    public function test_order_status_cases_and_values(): void
    {
        $this->assertSame(0, OrderStatus::Pending->value);
        $this->assertSame(1, OrderStatus::Processing->value);
        $this->assertSame(2, OrderStatus::Completed->value);
        $this->assertSame(3, OrderStatus::Canceled->value);
        $this->assertSame(4, OrderStatus::Shipping->value);
        $this->assertCount(5, OrderStatus::cases());
    }

    public function test_order_status_labels_and_array_format(): void
    {
        $this->assertSame('Chờ xác nhận', OrderStatus::Pending->label());
        $this->assertSame('Hoàn thành', OrderStatus::Completed->label());

        $asArray = OrderStatus::asArray();
        $this->assertCount(5, $asArray);
        $this->assertSame('Chờ xác nhận', $asArray[0]);
        $this->assertSame('Đang xử lý', $asArray[1]);
        $this->assertSame('Hoàn thành', $asArray[2]);
        $this->assertSame('Đã hủy', $asArray[3]);
        $this->assertSame('Đang giao hàng', $asArray[4]);
    }

    public function test_payment_status_cases_and_values(): void
    {
        $this->assertSame(0, PaymentStatus::Unpaid->value);
        $this->assertSame(1, PaymentStatus::Paid->value);
        $this->assertSame(2, PaymentStatus::Pending->value);
        $this->assertSame(3, PaymentStatus::Refunded->value);
        $this->assertSame(4, PaymentStatus::Expired->value);
        $this->assertSame(5, PaymentStatus::Failed->value);
        $this->assertCount(6, PaymentStatus::cases());
    }

    public function test_payment_status_labels_and_array_format(): void
    {
        $this->assertSame('Chưa thanh toán', PaymentStatus::Unpaid->label());
        $this->assertSame('Đã thanh toán', PaymentStatus::Paid->label());

        $asArray = PaymentStatus::asArray();
        $this->assertCount(6, $asArray);
        $this->assertSame('Chưa thanh toán', $asArray[0]);
        $this->assertSame('Đã thanh toán', $asArray[1]);
        $this->assertSame('Đang chờ', $asArray[2]);
        $this->assertSame('Hoàn tiền', $asArray[3]);
        $this->assertSame('Hết hạn', $asArray[4]);
        $this->assertSame('Thanh toán lỗi', $asArray[5]);
    }

    public function test_enum_try_from_safe_behavior(): void
    {
        $this->assertSame(OrderStatus::Pending, OrderStatus::tryFrom(0));
        $this->assertNull(OrderStatus::tryFrom(999));

        $this->assertSame(PaymentStatus::Unpaid, PaymentStatus::tryFrom(0));
        $this->assertNull(PaymentStatus::tryFrom(999));
    }
}
