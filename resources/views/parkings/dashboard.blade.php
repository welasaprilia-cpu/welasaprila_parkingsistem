@extends('layouts.app')

@section('title', 'Parking Spot Dashboard')

@section('content')
<!-- Minimal Clean Modern Dashboard -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 py-12 px-6 max-w-7xl mx-auto font-inter">

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-16 text-center lg:text-left">
        <div>
            <div class="flex items-center gap-4 justify-center lg:justify-start mb-6 lg:mb-4">
                <div class="w-20 h-20 bg-blue-500 rounded-3xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-tachometer-alt text-3xl text-white"></i>
                </div>
                <h1 class="text-6xl lg:text-7xl font-black text-gray-900">Dashboard</h1>
            </div>
            <p class="text-2xl text-gray-600 font-light max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                Selamat datang! Pantau aktivitas parkir secara real-time
            </p>
        </div>
        <a href="/parkings" class="px-12 py-8 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xl rounded-3xl shadow-2xl hover:shadow-3xl hover:-translate-y-2 transition-all duration-300 whitespace-nowrap">
            Kelola Parkir
        </a>
    </div>

    @php
        $totalSpots = $totalSpots ?? 12;
        $availableSpotsCount = $availableSpotsCount ?? 12;
        $used = max(0, $totalSpots - $availableSpotsCount);
        $percentage = $totalSpots > 0 ? round(($used / $totalSpots) * 100, 0) : 0;
        $paidRevenue = $paidRevenue ?? 0;
        $totalReservations = $totalReservations ?? 1;
    @endphp

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-24">
        
        <!-- Card 1: Kendaraan Aktif -->
        <div class="group bg-white rounded-3xl p-10 shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 cursor-pointer border border-gray-100 hover:border-blue-200">
            <div class="flex items-center gap-6 mb-6">
                <div class="w-20 h-20 bg-blue-100 rounded-3xl flex items-center justify-center group-hover:bg-blue-200 transition-all duration-300 shadow-lg">
                    <i class="fas fa-car text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Kendaraan Aktif</p>
                </div>
            </div>
            <h2 class="text-6xl lg:text-7xl font-black text-gray-900 mb-4">{{ $used }}</h2>
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full shadow-lg transition-all duration-700" style="width: {{ $percentage }}%"></div>
            </div>
            <p class="text-sm text-gray-500 mt-3 font-medium">{{ $percentage }}% dari {{ $totalSpots }} spot</p>
        </div>

        <!-- Card 2: Pendapatan -->
        <div class="group bg-white rounded-3xl p-10 shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 cursor-pointer border border-gray-100 hover:border-green-200">
            <div class="flex items-center gap-6 mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-3xl flex items-center justify-center group-hover:bg-green-200 transition-all duration-300 shadow-lg">
                    <i class="fas fa-wallet text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Pendapatan Hari Ini</p>
                </div>
            </div>
            <h2 class="text-6xl lg:text-7xl font-black text-gray-900 mb-2">
                Rp {{ number_format($paidRevenue, 0, ',', '.') }}
            </h2>
            <p class="text-lg text-gray-600 font-semibold">+{{ number_format(($paidRevenue / 1000), 0) }}rb bulan lalu</p>
        </div>

        <!-- Card 3: Total Transaksi -->
        <div class="group bg-white rounded-3xl p-10 shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 cursor-pointer border border-gray-100 hover:border-indigo-200">
            <div class="flex items-center gap-6 mb-6">
                <div class="w-20 h-20 bg-indigo-100 rounded-3xl flex items-center justify-center group-hover:bg-indigo-200 transition-all duration-300 shadow-lg">
                    <i class="fas fa-receipt text-2xl text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Transaksi</p>
                </div>
            </div>
            <h2 class="text-6xl lg:text-7xl font-black text-gray-900">{{ $totalReservations }}</h2>
        </div>

        <!-- Card 4: Kapasitas -->
        <div class="group bg-white rounded-3xl p-10 shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 cursor-pointer border border-gray-100 hover:border-yellow-200">
            <div class="flex items-center gap-6 mb-6">
                <div class="w-20 h-20 bg-yellow-100 rounded-3xl flex items-center justify-center group-hover:bg-yellow-200 transition-all duration-300 shadow-lg">
                    <i class="fas fa-percentage text-2xl text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Kapasitas Terpakai</p>
                </div>
            </div>
            <h2 class="text-6xl lg:text-7xl font-black text-gray-900">{{ $percentage }}%</h2>
            <div class="w-full bg-gray-200 rounded-full h-4 mt-4 overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-4 rounded-full shadow-lg transition-all duration-700" style="width: {{ $percentage }}%"></div>
            </div>
        </div>

    </div>

    <!-- CTA Button -->
    <div class="text-center">
        <a href="/spots" class="inline-flex items-center gap-4 px-16 py-10 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-2xl rounded-3xl shadow-2xl hover:shadow-3xl hover:-translate-y-4 transition-all duration-500 group bg-opacity-95 backdrop-blur-sm border border-white/20">
            Mulai Kelola Parkir
            <i class="fas fa-arrow-right text-xl group-hover:translate-x-2 transition-transform duration-300"></i>
        </a>
    </div>

</div>

<!-- Inter Font -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
body { font-family: 'Inter', sans-serif; }
</style>

@endsection
