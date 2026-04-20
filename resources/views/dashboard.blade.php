@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 lg:py-20 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-16 gap-6">
        <div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-tachometer-alt text-2xl text-white"></i>
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-gray-900">
                    Dashboard
                </h1>
            </div>
            <p class="text-xl md:text-2xl text-gray-600 font-light leading-relaxed max-w-2xl">
                Selamat datang! Pantau aktivitas parkir secara real-time
            </p>
        </div>

        <a href="{{ route('spots.index') }}"
           class="px-12 py-6 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xl rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 px-12 py-6">
            <i class="fas fa-parking mr-3"></i>Kelola Parkir
        </a>
    </div>

    @php
        $total = $totalSpots ?? 0;
        $available = $availableSpotsCount ?? 0;
        $used = max(0, $total - $available);
        $percentage = $total > 0 ? ($used / $total) * 100 : 0;
    @endphp

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
        
        <!-- Kendaraan Aktif -->
        <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer border border-gray-100 hover:border-blue-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-car text-2xl text-blue-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kendaraan Aktif</p>
            </div>
            <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-2">{{ $used }}</h2>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-500 h-3 rounded-full shadow-lg" style="width: {{ number_format($percentage, 0) }}%"></div>
            </div>
            <p class="text-sm text-gray-500 mt-2">{{ number_format($percentage, 0) }}% dari {{ $total }} spot</p>
        </div>

        <!-- Pendapatan -->
        <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer border border-gray-100 hover:border-green-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-wallet text-2xl text-green-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pendapatan Hari Ini</p>
            </div>
            <h2 class="text-4xl lg:text-5xl font-black text-gray-900">
                Rp {{ number_format($paidRevenue ?? 0, 0, ',', '.') }}
            </h2>
        </div>

        <!-- Total Transaksi -->
        <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer border border-gray-100 hover:border-indigo-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                    <i class="fas fa-receipt text-2xl text-indigo-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Transaksi</p>
            </div>
            <h2 class="text-4xl lg:text-5xl font-black text-gray-900">{{ $totalReservations ?? 0 }}</h2>
        </div>

        <!-- Kapasitas -->
        <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer border border-gray-100 hover:border-yellow-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                    <i class="fas fa-percentage text-2xl text-yellow-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kapasitas Terpakai</p>
            </div>
            <h2 class="text-4xl lg:text-5xl font-black text-gray-900">{{ number_format($percentage, 0) }}%</h2>
            <div class="w-full bg-gray-200 rounded-full h-3 mt-4">
                <div class="bg-yellow-500 h-3 rounded-full shadow-lg" style="width: {{ number_format($percentage, 0) }}%"></div>
            </div>
        </div>

    </div>

    <!-- CTA Button -->
    <div class="text-center">
        <a href="{{ route('spots.index') }}" class="inline-flex items-center gap-4 bg-blue-600 hover:bg-blue-700 text-white font-bold px-20 py-8 rounded-3xl text-2xl shadow-2xl hover:shadow-3xl hover:-translate-y-3 transition-all duration-300 group">
            Mulai Kelola Parkir
            <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform duration-300"></i>
        </a>
    </div>

</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
body {
    font-family: 'Inter', sans-serif;
}
</style>

@endsection
