<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use App\Models\Parking;

class SpotsController extends Controller
{
    public function index()
    {
        $spots = ParkingSpot::with(['currentParking', 'latestParking'])
            ->withCount('reservations')
            ->orderBy('floor')
            ->orderByRaw('LENGTH(spot_number)')
            ->orderBy('spot_number')
            ->get();

        $spotsByFloor = $spots->groupBy('floor');
        $availableSpots = ParkingSpot::where('is_available', true)
            ->orderBy('floor')
            ->orderByRaw('LENGTH(spot_number)')
            ->orderBy('spot_number')
            ->get();

        $activeCount = Parking::active()->count();
        $totalRevenue = Parking::whereNotNull('exit_time')->sum('price');
        $ratePerHour = 5000; // Rp 5,000 per jam

        return view('spots', compact('spots', 'spotsByFloor', 'availableSpots', 'activeCount', 'totalRevenue', 'ratePerHour'));
    }
}
