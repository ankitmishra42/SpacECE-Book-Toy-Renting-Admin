<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class StoreExport implements FromCollection
{
    public function __construct(
        public $store
    ) {
    }

    public function collection()
    {
        return $this->store;
    }
}
