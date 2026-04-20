<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create parking spots
        $defaultSpots = [
            ['spot_number' => 'A1', 'floor' => 1, 'location' => 'Lantai 1 - Spot A1', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'A2', 'floor' => 1, 'location' => 'Lantai 1 - Spot A2', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'A3', 'floor' => 1, 'location' => 'Lantai 1 - Spot A3', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'A4', 'floor' => 1, 'location' => 'Lantai 1 - Spot A4', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'A5', 'floor' => 1, 'location' => 'Lantai 1 - Spot A5', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'A6', 'floor' => 1, 'location' => 'Lantai 1 - Spot A6', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'A7', 'floor' => 1, 'location' => 'Lantai 1 - Spot A7', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'A8', 'floor' => 1, 'location' => 'Lantai 1 - Spot A8', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 5000],
            ['spot_number' => 'B1', 'floor' => 2, 'location' => 'Lantai 2 - Spot B1', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'B2', 'floor' => 2, 'location' => 'Lantai 2 - Spot B2', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'B3', 'floor' => 2, 'location' => 'Lantai 2 - Spot B3', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'B4', 'floor' => 2, 'location' => 'Lantai 2 - Spot B4', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'B5', 'floor' => 2, 'location' => 'Lantai 2 - Spot B5', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'B6', 'floor' => 2, 'location' => 'Lantai 2 - Spot B6', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'B7', 'floor' => 2, 'location' => 'Lantai 2 - Spot B7', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'B8', 'floor' => 2, 'location' => 'Lantai 2 - Spot B8', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 4000],
            ['spot_number' => 'C1', 'floor' => 3, 'location' => 'Lantai 3 - Spot C1', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
            ['spot_number' => 'C2', 'floor' => 3, 'location' => 'Lantai 3 - Spot C2', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
            ['spot_number' => 'C3', 'floor' => 3, 'location' => 'Lantai 3 - Spot C3', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
            ['spot_number' => 'C4', 'floor' => 3, 'location' => 'Lantai 3 - Spot C4', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
            ['spot_number' => 'C5', 'floor' => 3, 'location' => 'Lantai 3 - Spot C5', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
            ['spot_number' => 'C6', 'floor' => 3, 'location' => 'Lantai 3 - Spot C6', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
            ['spot_number' => 'C7', 'floor' => 3, 'location' => 'Lantai 3 - Spot C7', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
            ['spot_number' => 'C8', 'floor' => 3, 'location' => 'Lantai 3 - Spot C8', 'status' => 'available', 'is_available' => true, 'price_per_hour' => 3000],
        ];

        foreach ($defaultSpots as $spotData) {
            ParkingSpot::firstOrCreate(
                ['spot_number' => $spotData['spot_number'], 'floor' => $spotData['floor']],
                $spotData
            );
        }

        // Create users
        $admin = User::firstOrCreate(
            ['email' => 'welas@gmail.com'],
            [
                'name' => 'Welasaprilia',
                'password' => Hash::make('welas1234567'),
                'role' => 'admin',
            ]
        );

        $user1 = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('241103'),
                'role' => 'user',
            ]
        );

        // Create reservation example
        $reservedSpot = ParkingSpot::where('spot_number', 'B1')->where('floor', 2)->first();
        if ($reservedSpot) {
            $duration = 2;
            $startTime = Carbon::now();
            $endTime = (clone $startTime)->addHours($duration);
            $totalPrice = $reservedSpot->price_per_hour * $duration;

            $reservedSpot->markAsOccupied();

            Reservation::firstOrCreate(
                ['user_id' => $user1->id, 'parking_spot_id' => $reservedSpot->id],
                [
                    'plate_number' => 'B 1234 XYZ',
                    'vehicle_type' => 'car',
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => $duration,
                    'total_cost' => $totalPrice,
                    'total_price' => $totalPrice,
                    'reserved_at' => $startTime,
                ]
            );
        }

        // Create parking payment (fixed column mismatch)
        // Payment::create([
        //     'reservation_id' => $reservation->id,
        //     'amount' => 45.00,
        //     'status' => 'paid',
        //     'payment_method' => 'credit_card'
        // ]);

    }
}
