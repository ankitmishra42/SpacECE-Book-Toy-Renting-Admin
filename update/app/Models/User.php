<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $guarded = ['id'];
    public $guard_name = 'web';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    // --------------- Relationships ------------------
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(User::class, (new CouponUser())->getTable())
            ->withTimestamps();
    }

    public function profilePhoto(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'profile_photo_id');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'shop_owner');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'user_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(InvoiceManage::class, 'user_id');
    }

    public function devices()
    {
        return $this->hasMany(AdminDeviceKey::class);
    }

    // --------- scope ---------------
    public function scopeIsActive(Builder $builder, $isActive = true): Builder
    {
        return $builder->where('is_active', $isActive);
    }

    public function scopeIsVerified(Builder $builder): Builder
    {
        return $builder->whereNotNull('mobile_verified_at')
            ->orWhereNotNull('email_verified_at');
    }

    //    --------- Attributes ---------------
    public function name(): Attribute
    {
        return new Attribute(
            get: fn () => $this->first_name.' '.$this->last_name
        );
    }

    public function getProfilePhotoPathAttribute(): string
    {
        if ($this->profilePhoto && Storage::exists($this->profilePhoto->src)) {
            return Storage::url($this->profilePhoto->src);
        }

        return asset('images/dummy/dummy-user.png');
    }
}
