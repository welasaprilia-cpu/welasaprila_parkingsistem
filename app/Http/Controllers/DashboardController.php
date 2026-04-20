<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto create admin if not exists
        $admin = User::where('email', 'adminuser@gmail.com')->first();
        if (!$admin) {
            User::create([
                'name' => 'Admin',
                'email' => 'adminuser@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin'
            ]);
        }

$totalSpots = 0;
        $availableSpotsCount = 0;
        $totalUsers = User::count();
        $totalReservations = Reservation::count();
        $totalRevenue = Payment::sum('total_amount') ?? 0;
        $paidRevenue = Payment::where('status', 'paid')->sum('total_amount') ?? 0;

        $recentReservations = Reservation::with(['user', 'parkingSpot'])->latest()->take(5)->get();
        $spots = ParkingSpot::withCount('reservations')->get();

        return view('dashboard', compact(
            'totalSpots',
            'availableSpotsCount',
            'totalUsers',
            'totalReservations',
            'totalRevenue',
            'paidRevenue',
            'recentReservations',
            'spots'
        ));
    }
}

