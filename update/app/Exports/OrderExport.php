<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection
{
    use Exportable;

    public function __construct(
        public $orders
    ) {
    }

    public function collection()
    {
        return $this->orders;
    }
}
