@extends('layouts.app')

@section('title', 'Reservations - Parking System')

@section('content')
<div class="space-y-10">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-5xl font-black mb-2">Reservations</h1>
            <p class="text-2xl text-gray-400">Manage booking and reservations</p>
        </div>
        <a href="{{ route('reservations.create') }}" class="btn px-12 py-6 text-xl shadow-2xl no-underline inline-block">
            <i class="fas fa-calendar-plus mr-3"></i>
            New Reservation
        </a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Reservation</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">User</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Spot</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Duration</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Total Price</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Reserved At</th>
                        <th class="text-left py-8 font-bold text-xl uppercase tracking-wider text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
@forelse($reservations as $reservation)
                    <tr class="hover:bg-white/5 transition-all">
                        <td class="py-8 pr-8">
                            <div class="font-mono text-xl font-bold text-blue-400">#{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-sm text-gray-400 mt-1">Created {{ $reservation->created_at->diffForHumans() }}</div>
                            <div class="mt-3">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $reservation->source === 'parking' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $reservation->source_label }}
                                </span>
                            </div>
                        </td>
                        <td class="py-8 pr-8">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-sm font-bold text-white">
                                    {{ strtoupper(substr($reservation->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-lg">{{ $reservation->user->name }}</p>
                                    <p class="text-sm text-gray-400">{{ $reservation->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-8 pr-8">
                            <span class="status-badge status-{{ $reservation->parkingSpot->status }}">
                                {{ $reservation->parkingSpot->location }}
                            </span>
                        </td>
                        <td class="py-8 pr-8">
                            <p class="text-lg font-bold">{{ $reservation->duration }} jam</p>
                        </td>
                        <td class="py-8 pr-8">
                            <p class="text-2xl font-bold text-emerald-400">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-8 pr-8">
                            <p class="text-lg font-mono">{{ $reservation->reserved_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="py-8">
                            <div class="flex gap-3">
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-emerald px-6 py-3 text-sm inline-block no-underline">
                                    <i class="fas fa-eye mr-2"></i>View
                                </a>
                                <a href="{{ route('reservations.edit', $reservation) }}" class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-3 text-sm rounded-2xl font-semibold text-white shadow-lg inline-block no-underline">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline" onsubmit="return confirm('Hapus reservation ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-3 text-sm rounded-2xl font-semibold shadow-lg transition"> 
                                        <i class="fas fa-trash mr-2"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-4 block"></i>
                            Belum ada reservasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-12 flex justify-center">
{{ $reservations->links() }}
        </div>
    </div>
</div>
@endsection

