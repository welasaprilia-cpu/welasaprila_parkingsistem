<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use App\Models\Payment;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ParkingController extends Controller
{
    private const MAX_EXIT_HOUR = 22;

    private function isPastOperatingLimit(Carbon $time): bool
    {
        return $time->hour >= self::MAX_EXIT_HOUR;
    }

    private function buildMaxExitTime(Carbon $startTime): Carbon
    {
        return $startTime->copy()->setTime(self::MAX_EXIT_HOUR, 0, 0);
    }

    private function buildReservationDuration(Carbon $startTime, Carbon $maxExitTime): int
    {
        return (int) ceil(max(1, $startTime->diffInMinutes($maxExitTime)) / 60);
    }

    private function mapVehicleTypeToReservation(string $vehicleType): string
    {
        return match ($vehicleType) {
            'mobil' => 'car',
            'motor' => 'motorcycle',
            'truk' => 'truck',
            default => 'car',
        };
    }


    public function index()
    {
        $activeVehicles = Parking::with('parkingSpot')->active()->latest('entry_time')->get();
        $availableSpots = ParkingSpot::where('is_available', true)
            ->orderBy('floor')
            ->orderByRaw('LENGTH(spot_number)')
            ->orderBy('spot_number')
            ->get();

        $totalActive = Parking::active()->count();
        $totalRevenue = Parking::whereNotNull('exit_time')->sum('price');
        $ratePerHour = ParkingSpot::min('price_per_hour') ?? 5000;
        $recentCompleted = Parking::with('parkingSpot')
            ->whereNotNull('exit_time')
            ->latest('exit_time')
            ->take(6)
            ->get();

        return view('parking.dashboard', compact(
            'activeVehicles',
            'availableSpots',
            'totalActive',
            'totalRevenue',
            'ratePerHour',
            'recentCompleted'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
            'vehicle_type' => 'required|in:mobil,motor,truk',
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'entry_photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $spot = ParkingSpot::where('id', $request->parking_spot_id)
            ->where('is_available', true)
            ->first();

        if (! $spot) {
            return redirect()->back()->withInput()->with('error', 'Spot parkir yang dipilih sudah terisi.');
        }

        $startTime = now();
        $maxExitTime = $this->buildMaxExitTime($startTime);

        if ($this->isPastOperatingLimit($startTime)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Kendaraan masuk hanya bisa diproses sebelum pukul 22.00.');
        }

        try {
            DB::transaction(function () use ($request, $spot, $startTime, $maxExitTime) {
                $fixedPrice = (int) $spot->price_per_hour;
                $reservationDuration = $this->buildReservationDuration($startTime, $maxExitTime);
                $entryPhotoPath = $request->file('entry_photo')->store('parking-photos', 'public');

                $spot->markAsOccupied();

                $newParking = Parking::create([
                    'plate_number' => strtoupper($request->plate_number),
                    'vehicle_type' => $request->vehicle_type,
                    'parking_spot_id' => $spot->id,
                    'entry_time' => $startTime,
                    'price' => $fixedPrice,
                    'entry_photo_path' => $entryPhotoPath,
                    'max_exit_time' => $maxExitTime,
                ]);

                // Create paid payment on entry (pre-pay cash)
                Payment::create([
                    'parking_id' => $newParking->id,
                    'plate_number' => $newParking->plate_number,
                    'entry_time' => $startTime,
                    'duration' => $reservationDuration,
                    'total_amount' => $fixedPrice,
                    'total_bayar' => $fixedPrice,
                    'status' => 'paid',
                    'payment_method' => 'cash',
                ]);

                Reservation::create([
                    'user_id' => auth()->id(),
                    'parking_spot_id' => $spot->id,
                    'source' => 'parking',
                    'plate_number' => strtoupper($request->plate_number),
                    'vehicle_type' => $this->mapVehicleTypeToReservation($request->vehicle_type),
                    'start_time' => $startTime,
                    'end_time' => $maxExitTime,
                    'duration' => $reservationDuration,
                    'total_cost' => $fixedPrice,
                    'total_price' => $fixedPrice,
                    'reserved_at' => $startTime,
                ]);
            });
        } catch (QueryException $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Foto belum bisa disimpan karena tabel database belum siap. Jalankan migration lalu coba lagi.');
        }

        return redirect()->back()->with('success', 'Kendaraan berhasil masuk. Tarif tetap dikunci sampai kendaraan keluar, dengan batas parkir pukul 22.00.');
    }

    public function exit(Request $request, Parking $parking)
    {
        $request->validate([
            'exit_photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($parking->exit_time) {
            return redirect()->back()->with('error', 'Kendaraan sudah keluar.');
        }

        $entry = Carbon::parse($parking->entry_time);
        $exit = Carbon::now();

        if ($this->isPastOperatingLimit($exit)) {
            return redirect()->back()->with('error', 'Kendaraan keluar hanya bisa diproses sebelum pukul 22.00.');
        }

        $duration = (int) ceil(max(1, $entry->diffInMinutes($exit)) / 60);
        $price = (float) $parking->price;
        $exitPhotoPath = $request->file('exit_photo')->store('parking-photos', 'public');

        try {
            DB::transaction(function () use ($parking, $exit, $price, $duration, $exitPhotoPath) {
                $parking->update([
                    'exit_time' => $exit,
                    'check_out' => $exit,
                    'price' => $price,
                    'exit_photo_path' => $exitPhotoPath,
                ]);

                // Update existing entry payment with exit details
                Payment::where('parking_id', $parking->id)
                    ->where('status', 'paid')
                    ->update([
                        'exit_time' => $exit,
                        'duration' => $duration,
                        'total_amount' => $price,
                        'total_bayar' => $price,
                    ]);

                $activeReservation = Reservation::with('parkingSpot')
                    ->where('plate_number', $parking->plate_number)
                    ->where('parking_spot_id', $parking->parking_spot_id)
                    ->latest('reserved_at')
                    ->first();

                if ($activeReservation) {
                    $activeReservation->update([
                        'start_time' => $parking->entry_time,
                        'end_time' => $exit,
                        'duration' => $duration,
                        'total_cost' => $price,
                        'total_price' => $price,
                    ]);
                }

                if ($activeReservation?->parkingSpot) {
                    $activeReservation->parkingSpot->markAsAvailable();
                }

                if ($parking->parkingSpot) {
                    $parking->parkingSpot->markAsAvailable();
                }
            });
        } catch (QueryException $exception) {
            return redirect()->back()
                ->with('error', 'Foto keluar belum bisa disimpan karena tabel database belum siap. Jalankan migration lalu coba lagi.');
        }

        $message = 'Kendaraan berhasil keluar. Total: Rp ' . number_format($price, 0, ',', '.');

        return redirect()->back()->with('success', $message);
    }
}

