<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(array $arr)
    {

        $this->arr = $arr;

    }

    public function collection()
    {

        return collect($this->arr);

    }

    public function headings(): array
    {

        return [

            'Mã đơn hàng',

            'Ngày đặt hàng',

            'Tên khách hàng',

            'Email',

            'Số điện thoại',

            'Tỉnh/Thành phố',

            'Quận/Huyện',

            'Phường/Xã',

            'Địa chỉ',

            'Mã sản phẩm',

            'Sản phẩm',

            'Số lượng',

            'Hình thức thanh toán',

            'Tổng tiền',

            'Phí vận chuyển',

            'Trạng thái',

        ];

    }

    public function map($row): array
    {

        return [

            $row['Order_Code'],

            $row['Order_Date'],

            $row['Customer'],

            $row['Email'],

            $row['Tel'],

            $row['Province'],

            $row['District'],

            $row['Ward'],

            $row['Address'],

            $row['Product_Code'],

            $row['Product_Name'],

            $row['Product_Quantity'],

            $row['Pay_Method'],

            $row['Total'],

            $row['Shipping_Fee'],

            $row['Status'],

        ];

    }
}
