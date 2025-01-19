<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'status',
        'vehicle_number',
        'vehicle_type',
        'license_number',
        'photo',
        'active_orders'
    ];

    protected $casts = [
        'active_orders' => 'integer',
        'status' => 'boolean',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', true)
            ->where('active_orders', '<', config('courier.max_active_orders', 5));
    }

    // Accessors & Mutators
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('images/default-courier.png');
    }

    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function getAvailabilityStatusAttribute()
    {
        if (!$this->status) {
            return 'Inactive';
        }
        return $this->active_orders < config('courier.max_active_orders', 5) ? 'Available' : 'Busy';
    }
} 