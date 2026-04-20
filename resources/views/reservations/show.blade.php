@extends('layouts.app')

@section('title', 'Reservation Detail')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Reservation Detail</h1>
                <div class="font-mono text-xl font-bold text-blue-600">#{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
            <a href="{{ route('reservations.index') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                ← Back to List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Customer Info</h3>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-xl font-bold text-white">
                            {{ strtoupper(substr($reservation->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-xl">{{ $reservation->user->name }}</h4>
                            <p class="text-gray-600">{{ $reservation->user->email }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Parking Spot</h3>
                    <div class="p-6 bg-emerald-50 border-2 border-emerald-200 rounded-2xl">
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-bold text-emerald-700">{{ $reservation->parkingSpot->location }}</span>
                            <span class="status-badge status-{{ $reservation->parkingSpot->status }}">
                                {{ ucfirst($reservation->parkingSpot->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-emerald-600 mt-2">Rp {{ number_format($reservation->parkingSpot->price_per_hour, 0, ',', '.') }} / jam</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Schedule</h3>
                    <div class="p-6 bg-blue-50 border-2 border-blue-200 rounded-2xl">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <p class="text-sm text-blue-600 uppercase font-semibold tracking-wide">Start</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $reservation->start_time->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600 uppercase font-semibold tracking-wide">End</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $reservation->end_time->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-white rounded-xl border">
                            <p class="text-lg font-bold text-gray-900">Total: Rp {{ number_format($reservation->total_cost, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Status</h3>
                    <div class="flex items-center justify-between p-6 bg-gray-50 rounded-2xl">
                        <span class="text-xl font-bold text-gray-800">Pending Payment</span>
                        <span class="status-badge bg-orange-100 text-orange-800 border-orange-200 px-4 py-2 font-semibold">Awaiting</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-12 pt-12 border-t border-gray-200">
            <a href="{{ route('reservations.edit', $reservation) }}" class="flex-1 text-center py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                Edit Reservation
            </a>
            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this reservation?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-bold text-lg hover:from-red-600 hover:to-red-700 transition shadow-lg">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
