<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreService extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
