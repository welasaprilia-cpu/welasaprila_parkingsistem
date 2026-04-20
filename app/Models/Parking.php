<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'entry_time',
        'exit_time',
        'price',
        'parking_spot_id',
        'entry_photo_path',
        'exit_photo_path',
        'max_exit_time',
        'vehicle_number',
        'check_in',
        'check_out',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'max_exit_time' => 'datetime',
        'price' => 'decimal:2',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function getStatusAttribute()
    {
        return $this->exit_time ? 'completed' : 'active';
    }

    public function getDurationAttribute()
    {
        if (! $this->entry_time) {
            return 0;
        }

        $entry = Carbon::parse($this->entry_time);
        $exit = $this->exit_time ? Carbon::parse($this->exit_time) : Carbon::now();

        return (int) ceil(max(1, $entry->diffInMinutes($exit)) / 60);
    }

    public function getElapsedMinutesAttribute(): int
    {
        if (! $this->entry_time) {
            return 0;
        }

        $exit = $this->exit_time ?? Carbon::now();

        return Carbon::parse($this->entry_time)->diffInMinutes($exit);
    }

    public function getIsOverLimitAttribute(): bool
    {
        return $this->max_exit_time !== null && ($this->exit_time ?? Carbon::now())->greaterThan($this->max_exit_time);
    }

    public function getEntryPhotoUrlAttribute(): ?string
    {
        return $this->entry_photo_path ? Storage::disk('public')->url($this->entry_photo_path) : null;
    }

    public function getExitPhotoUrlAttribute(): ?string
    {
        return $this->exit_photo_path ? Storage::disk('public')->url($this->exit_photo_path) : null;
    }

    public function scopeActive($query)
    {
        return $query->whereNull('exit_time');
    }

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'parking_id');
    }
}
