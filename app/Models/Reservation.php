<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'parking_spot_id',
        'source',
        'plate_number',
        'vehicle_type',
        'start_time',
        'end_time',
        'duration',
        'total_cost',
        'total_price',
        'reserved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration' => 'integer',
        'total_cost' => 'integer',
        'total_price' => 'integer',
        'reserved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getSourceLabelAttribute(): string
    {
        return $this->source === 'parking' ? 'Dari Parkir' : 'Manual';
    }
}
