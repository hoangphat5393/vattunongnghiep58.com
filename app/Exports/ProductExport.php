<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
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

            'ID',

            'Tên Sản Phẩm',

            'Danh Mục',

            'Giá Gốc',

            'Giá Khuyến Mãi',

            'Start Event',

            'End Event',

            'Khối Lượng',

            'Keyword',

        ];

    }

    public function map($row): array
    {

        return [

            $row['ID'],

            $row['Title'],

            $row['Category'],

            $row['Price_Origin'],

            $row['Price_Promotion'],

            $row['Start_Event'],

            $row['End_Event'],

            $row['Weight'],

            $row['Keyword'],

        ];

    }
}
