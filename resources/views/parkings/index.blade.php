@extends('layouts.app')

@section('title', 'Parkings')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-5xl font-black mb-2 bg-gradient-to-r from-purple-500 to-blue-600 bg-clip-text text-transparent">Parkings</h1>
            <p class="text-2xl text-gray-400">Digital parking management system</p>
        </div>
        <button class="btn px-12 py-6 text-xl shadow-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700">
            <i class="fas fa-plus mr-3"></i>
            Entry Kendaraan Baru
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Active Parking -->
        <div class="card p-12 rounded-3xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 border-blue-200">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-3xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-car-side text-3xl text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-blue-400 uppercase tracking-wide mb-2">Aktif</p>
                    <p class="text-5xl font-black text-gray-900">{{ $activeCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="card p-12 rounded-3xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border-emerald-200">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-3xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-coins text-3xl text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-emerald-400 uppercase tracking-wide mb-2">Pendapatan</p>
                    <p class="text-5xl font-black text-gray-900">Rp {{ number_format($revenue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="card p-12 rounded-3xl bg-gradient-to-br from-purple-500/10 to-pink-500/10 border-purple-200">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-600 rounded-3xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-receipt text-3xl text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-purple-400 uppercase tracking-wide mb-2">Transaksi</p>
                    <p class="text-5xl font-black text-gray-900">{{ $totalTransactions ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Capacity -->
        <div class="card p-12 rounded-3xl bg-gradient-to-br from-orange-500/10 to-red-500/10 border-orange-200">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 bg-gradient-to-br from-orange-400 to-red-600 rounded-3xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-percentage text-3xl text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-orange-400 uppercase tracking-wide mb-2">Kapasitas</p>
                    <p class="text-5xl font-black text-gray-900">{{ $capacityUsed ?? 0 }}%</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Entry Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        
        <!-- Entry Form -->
        <div class="card p-12 lg:col-span-1 rounded-3xl">
            <h2 class="text-4xl font-black mb-8 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Entry Kendaraan Baru</h2>
            <form class="space-y-6">
                <div>
                    <label class="block text-xl font-semibold text-gray-700 mb-4">Nomor Plat Kendaraan</label>
                    <input type="text" placeholder="B 1234 XYZ" 
                           class="w-full p-6 border-2 border-gray-200 rounded-3xl text-xl font-semibold focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100 transition-all shadow-lg text-gray-900">
                </div>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold text-2xl py-8 px-12 rounded-3xl shadow-2xl hover:shadow-3xl hover:-translate-y-2 transition-all duration-300 flex items-center justify-center gap-4">
                    <i class="fas fa-arrow-right text-xl"></i>
                    Parkir Masuk
                </button>
            </form>
        </div>

        <!-- Stats Card -->
        <div class="card p-12 rounded-3xl bg-gradient-to-br from-blue-500 to-emerald-500 text-white lg:col-span-1">
            <h2 class="text-4xl font-black mb-12">Statistik Real-time</h2>
            <div class="space-y-8">
                <div class="flex items-center gap-6 p-8 bg-white/20 backdrop-blur rounded-3xl">
                    <div class="w-20 h-20 bg-white/30 rounded-3xl flex items-center justify-center">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-5xl font-black">0 Aktif</p>
                        <p class="text-xl opacity-90">Kendaraan parkir</p>
                    </div>
                </div>
                <div class="border-t border-white/30 pt-8">
                    <div class="flex items-center gap-6 p-8 bg-white/20 backdrop-blur rounded-3xl">
                        <div class="w-20 h-20 bg-white/30 rounded-3xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-5xl font-black">Rp 14.000</p>
                            <p class="text-xl opacity-90">Total Pendapatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Current Vehicles Section -->
    <div class="card p-12 rounded-3xl">
        <div class="flex items-center gap-4 mb-12">
            <i class="fas fa-car text-4xl text-blue-500"></i>
            <h2 class="text-4xl font-black">Kendaraan Sedang Parkir ({{ $activeCount ?? 0 }})</h2>
        </div>
        
        @if(($activeCount ?? 0) === 0)
            <div class="text-center py-24">
                <i class="fas fa-parking text-8xl text-gray-300 mb-8"></i>
                <p class="text-2xl text-gray-500 font-semibold">Belum ada kendaraan yang parkir</p>
            </div>
        @else
            <!-- Table or cards for active vehicles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Active vehicle cards will go here -->
            </div>
        @endif
    </div>

</div>
@endsection
