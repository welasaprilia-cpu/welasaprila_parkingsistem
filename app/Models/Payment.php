<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'parking_payments';

    use HasFactory;

   
    protected $fillable = [
        'plate_number',
        'entry_time',
        'exit_time',
        'duration',
        'total_amount',
        'status',
        'payment_method',
        'parking_id',
        'total_bayar',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'total_amount' => 'decimal:2',
        'total_bayar' => 'decimal:2',
    ];

    public function getDurationHoursAttribute()
    {
        if (!$this->entry_time || !$this->exit_time) return 0;
        return $this->entry_time->diffInHours($this->exit_time);
    }

    public function getCalculatedTotalBayarAttribute()
    {
        return $this->duration * 5000;
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class, 'parking_id');
    }
}
