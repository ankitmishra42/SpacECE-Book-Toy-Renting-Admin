<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // ---------- Relations
    public function products()
    {
        return $this->hasMany(Product::class, 'variant_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
