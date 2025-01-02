<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->hasMany(Category::class, 'thumbnail_id');
    }

    public function file(): Attribute
    {
        $defualt = Storage::exists($this->src) ? Storage::url($this->src) : asset('images/dummy/dummy-placeholder.png');

        return Attribute::make(
            get: fn () => $defualt,
        );
    }
}
