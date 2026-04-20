<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationsController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'parkingSpot'])->latest()->paginate(10);
        return view('reservations', compact('reservations'));
    }

    public function create(Request $request)
    {
        $floor = intval($request->query('floor', 1));
        if (!in_array($floor, [1, 2, 3])) {
            $floor = 1;
        }

        $spots = ParkingSpot::where('floor', $floor)
            ->where('is_available', true)
            ->orderByRaw('LENGTH(spot_number)')
            ->orderBy('spot_number')
            ->get();

        return view('reservations.create', compact('spots', 'floor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'plate_number' => 'required|string|max:20',
            'vehicle_type' => 'required|in:car,motorcycle,truck',
            'duration' => 'required|integer|min:1',
        ]);

        $spot = ParkingSpot::where('id', $request->parking_spot_id)
            ->where('is_available', true)
            ->first();

        if (!$spot) {
            return redirect()->back()->with('error', 'Spot parkir tidak tersedia atau sudah dipesan.');
        }

        $duration = (int) $request->duration;
        $totalPrice = $spot->price_per_hour * $duration;
        $startTime = now();
        $endTime = (clone $startTime)->addHours($duration);

        DB::transaction(function () use ($spot, $request, $duration, $totalPrice, $startTime, $endTime) {
            $spot->markAsOccupied();

            Reservation::create([
                'user_id' => auth()->id(),
                'parking_spot_id' => $spot->id,
                'source' => 'manual',
                'plate_number' => strtoupper($request->plate_number),
                'vehicle_type' => $request->vehicle_type,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration' => $duration,
                'total_cost' => $totalPrice,
                'total_price' => $totalPrice,
                'reserved_at' => $startTime,
            ]);
        });

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil disimpan.');
    }

    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $parkingSpots = ParkingSpot::where(function ($query) use ($reservation) {
            $query->where('is_available', true)
                ->orWhere('id', $reservation->parking_spot_id);
        })
            ->orderBy('floor')
            ->orderBy('spot_number')
            ->get();

        return view('reservations.edit', compact('reservation', 'parkingSpots'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'vehicle_type' => 'required|in:car,motorcycle,truck',
            'duration' => 'required|integer|min:1',
        ]);

        $currentSpot = $reservation->parkingSpot;
        $newSpot = ParkingSpot::where('id', $request->parking_spot_id)
            ->where(function ($query) use ($reservation) {
                $query->where('is_available', true)
                      ->orWhere('id', $reservation->parking_spot_id);
            })
            ->first();

        if (!$newSpot) {
            return redirect()->back()->with('error', 'Spot parkir tidak tersedia atau sudah dipesan.');
        }

        $duration = (int) $request->duration;
        $startTime = Carbon::parse($request->reservation_date . ' ' . $request->reservation_time);
        $endTime = (clone $startTime)->addHours($duration);
        $totalPrice = $newSpot->price_per_hour * $duration;

        DB::transaction(function () use ($reservation, $currentSpot, $newSpot, $request, $duration, $startTime, $endTime, $totalPrice) {
            if ($currentSpot && $currentSpot->id !== $newSpot->id) {
                $currentSpot->markAsAvailable();
                $newSpot->markAsOccupied();
            } elseif ($currentSpot && $currentSpot->id === $newSpot->id) {
                $newSpot->markAsOccupied();
            }

            $reservation->update([
                'parking_spot_id' => $newSpot->id,
                'source' => $reservation->source ?? 'manual',
                'vehicle_type' => $request->vehicle_type,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration' => $duration,
                'total_cost' => $totalPrice,
                'total_price' => $totalPrice,
                'reserved_at' => now(),
            ]);
        });

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        DB::transaction(function () use ($reservation) {
            if ($reservation->parkingSpot) {
                $reservation->parkingSpot->markAsAvailable();
            }

            $reservation->delete();
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation dihapus dan spot parkir dibuka kembali.');
    }
}

