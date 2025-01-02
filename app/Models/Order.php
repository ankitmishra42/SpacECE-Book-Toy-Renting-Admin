<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'order_status' => OrderStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, (new OrderProduct())->getTable())
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function additionals()
    {
        return $this->belongsToMany(Additional::class, (new AdditionalOrder())->getTable());
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(DriverOrder::class, 'order_id');
    }

    public function invoiceDownload(): Attribute
    {
        $invoice = null;
        if ($this->invoice_path && Storage::exists($this->invoice_path)) {
            $invoice = Storage::url($this->invoice_path);
        }

        return new Attribute(
            get: fn () => $invoice
        );
    }

    public static function getTime($time)
    {
        $times = [
            '8' => '08 - 09:59',
            '9' => '08 - 09:59',
            '10' => '10 - 11:59',
            '11' => '10 - 11:59',
            '12' => '12 - 13:59',
            '13' => '12 - 13:59',
            '14' => '14 - 15:59',
            '15' => '14 -1 5:59',
            '16' => '16 - 17:59',
            '17' => '16 - 17:59',
            '18' => '18 - 19:59',
            '19' => '18 - 19:59',
            '20' => '20 - 21:59',
            '21' => '20 - 21:59',
        ];
        foreach ($times as $key => $item) {
            if ($key == $time) {
                return $item;
            }
        }
    }

    /**
     * Boot method to add a global scope to the model.
     *
     * This global scope filters out all orders that are marked as not POS orders.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('pos', function (Builder $builder) {
          return $builder->where('pos_order', 0);
        });
    }
}
