<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'spot_number',
        'floor',
        'location',
        'status',
        'is_available',
        'price_per_hour',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_per_hour' => 'integer',
        'is_available' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function currentParking()
    {
        return $this->hasOne(Parking::class)->active();
    }

    public function latestParking()
    {
        return $this->hasOne(Parking::class)->latestOfMany('entry_time');
    }

    public function markAsAvailable(): void
    {
        $this->update([
            'is_available' => true,
            'status' => 'available',
        ]);
    }

    public function markAsOccupied(): void
    {
        $this->update([
            'is_available' => false,
            'status' => 'occupied',
        ]);
    }
}
