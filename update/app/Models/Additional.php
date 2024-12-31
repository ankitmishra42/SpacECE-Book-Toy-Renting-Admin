<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, (new AdditionalOrder())->getTable());
    }
}
