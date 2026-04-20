@extends('layouts.app')

@section('title', 'Edit Reservation')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h1 class="text-3xl font-bold mb-8">Edit Reservation #{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</h1>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('reservations.update', $reservation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Plat (tidak bisa diubah)</label>
                    <input type="text" value="{{ $reservation->plate_number ?? '' }}" class="w-full p-4 bg-gray-100 border border-gray-300 rounded-xl cursor-not-allowed" readonly>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="reservation_date" value="{{ old('reservation_date', optional($reservation->start_time)->format('Y-m-d')) }}" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu</label>
                        <input type="time" name="reservation_time" value="{{ old('reservation_time', optional($reservation->start_time)->format('H:i')) }}" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Spot Parkir</label>
                    <select name="parking_spot_id" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Pilih Spot</option>
                        @foreach($parkingSpots as $spot)
                            <option value="{{ $spot->id }}" {{ (string) old('parking_spot_id', $reservation->parking_spot_id) === (string) $spot->id ? 'selected' : '' }}>
                                {{ $spot->spot_number }} - Lantai {{ $spot->floor }} - {{ $spot->is_available || $spot->id === $reservation->parking_spot_id ? 'Tersedia' : 'Terisi' }}
                            </option>
                        @endforeach
                    </select>
                    @error('parking_spot_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Parkir (jam)</label>
                    <input type="number" name="duration" value="{{ old('duration', $reservation->duration ?? 1) }}" min="1" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('duration')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kendaraan</label>
                    <select name="vehicle_type" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="car" {{ old('vehicle_type', $reservation->vehicle_type ?? '') == 'car' ? 'selected' : '' }}>Mobil</option>
                        <option value="motorcycle" {{ old('vehicle_type', $reservation->vehicle_type ?? '') == 'motorcycle' ? 'selected' : '' }}>Motor</option>
                        <option value="truck" {{ old('vehicle_type', $reservation->vehicle_type ?? '') == 'truck' ? 'selected' : '' }}>Truk</option>
                    </select>
                    @error('vehicle_type')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <a href="{{ route('reservations.index') }}" class="flex-1 text-center py-4 px-8 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition">
                    Cancel
                </a>
                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-4 px-8 rounded-xl font-bold text-lg shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition">
                    Update Reservation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
