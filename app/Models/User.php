<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'balance',
        'phone',
        'class',
        'parent_id',
        'daily_limit',
        'qr_code',
        'status',
        'email_notifications',
        'push_notifications',
        'low_balance_alert',
        'transaction_alert',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'low_balance_alert' => 'boolean',
        'transaction_alert' => 'boolean',
    ];

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_KANTIN_ADMIN = 'kantin_admin';
    const ROLE_KANTIN_STAFF = 'kantin_staff';
    const ROLE_TEACHER = 'teacher';
    const ROLE_PARENT = 'parent';
    const ROLE_STUDENT = 'student';

    /**
     * Get available roles
     */
    public static function getAvailableRoles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_KANTIN_ADMIN,
            self::ROLE_KANTIN_STAFF,
            self::ROLE_TEACHER,
            self::ROLE_PARENT,
            self::ROLE_STUDENT,
            'kasir',
            'owner'
        ];
    }

    // Role checkers
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKantinAdmin()
    {
        return $this->role === self::ROLE_KANTIN_ADMIN;
    }

    public function isKantinStaff()
    {
        return $this->role === self::ROLE_KANTIN_STAFF;
    }

    public function isTeacher()
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isParent()
    {
        return $this->role === self::ROLE_PARENT;
    }

    public function isStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // QR Code generation
    public function generateQrCode()
    {
        $this->qr_code = Str::random(32);
        $this->save();
    }

    // Check daily limit
    public function checkDailyLimit($amount)
    {
        if (!$this->daily_limit) {
            return true;
        }

        $todaySpent = $this->orders()
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        return ($todaySpent + $amount) <= $this->daily_limit;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function todayOrders()
    {
        return $this->orders()
            ->whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_amount');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($user) {
            if (!in_array($user->role, self::getAvailableRoles())) {
                throw new \InvalidArgumentException('Invalid role specified');
            }
        });
    }
}
