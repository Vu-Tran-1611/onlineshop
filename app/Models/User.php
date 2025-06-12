<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ShopProfile;
use App\Models\Order;
use App\Models\UserAddresses;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'username',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function shop_profile()
    {
        return $this->hasOne(ShopProfile::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function addresses()
    {
        return $this->hasMany(UserAddresses::class);
    }
    // public function wishlists()
    // {
    //     return $this->hasMany(Wishlist::class);
    // }
    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }
    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }
    // public function cart()
    // {
    //     return $this->hasOne(Cart::class);
    // }
    // public function paymentMethods()
    // {
    //     return $this->hasMany(PaymentMethod::class);
    // }
    // public function subscriptions()
    // {
    //     return $this->hasMany(Subscription::class);
    // }
    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }
    // public function coupons()
    // {
    //     return $this->hasMany(Coupon::class);
    // }
    // public function shippingAddresses()
    // {
    //     return $this->hasMany(ShippingAddress::class);
    // }
    // public function billingAddresses()
    // {
    //     return $this->hasMany(BillingAddress::class);
    // }
    // public function paymentMethods()
    // {
    //     return $this->hasMany(PaymentMethod::class);
    // }
    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }
}
