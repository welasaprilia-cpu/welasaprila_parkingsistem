@extends('layouts.app')

@section('title', 'Manajemen Parkir')

@section('content')
<div class="space-y-8">
    <div class="rounded-3xl bg-gradient-to-r from-sky-600 via-cyan-600 to-emerald-600 p-8 text-white shadow-xl">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-100">Operasional Parkir</p>
                <h1 class="mt-3 text-4xl font-black">Masuk, keluar, foto kendaraan, dan laporan dalam satu halaman</h1>
                <p class="mt-3 max-w-3xl text-base text-cyan-50">
                    Tarif parkir dikunci saat kendaraan masuk dan tetap sama sampai keluar. Sistem juga menandai kendaraan yang melewati batas parkir pukul 22.00.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="rounded-2xl bg-white/15 px-6 py-5 backdrop-blur">
                    <p class="text-sm text-cyan-100">Tarif tetap</p>
                    <p class="mt-2 text-2xl font-black">Rp {{ number_format($ratePerHour, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl bg-white/15 px-6 py-5 backdrop-blur">
                    <p class="text-sm text-cyan-100">Batas parkir</p>
                    <p class="mt-2 text-2xl font-black">22.00</p>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 shadow-sm">
            <p class="font-semibold">Data belum lengkap.</p>
            <ul class="mt-2 list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Kendaraan Aktif</p>
            <p class="mt-3 text-4xl font-black text-slate-900">{{ $totalActive }}</p>
            <p class="mt-2 text-sm text-slate-500">Sedang menggunakan slot parkir sekarang.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Pendapatan Total</p>
            <p class="mt-3 text-4xl font-black text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="mt-2 text-sm text-slate-500">Akumulasi dari kendaraan yang sudah keluar.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Spot Tersedia</p>
            <p class="mt-3 text-4xl font-black text-slate-900">{{ $availableSpots->count() }}</p>
            <p class="mt-2 text-sm text-slate-500">Bisa langsung dipilih untuk kendaraan baru.</p>
        </div>
    </div>

    <div class="grid gap-8 xl:grid-cols-[1.1fr,1.9fr]">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-900">Kendaraan Masuk</h2>
                <p class="mt-2 text-sm text-slate-500">Upload foto kendaraan saat masuk, pilih jenis kendaraan, lalu pilih spot yang masih tersedia.</p>
            </div>

            <form method="POST" action="{{ route('parking.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nomor Plat</label>
                    <input
                        type="text"
                        name="plate_number"
                        value="{{ old('plate_number') }}"
                        placeholder="B 1234 XYZ"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                        required
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis Kendaraan</label>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <label class="vehicle-card cursor-pointer rounded-2xl border border-slate-200 p-4 transition hover:border-cyan-400 hover:bg-cyan-50">
                            <input type="radio" name="vehicle_type" value="mobil" class="sr-only" {{ old('vehicle_type') === 'mobil' ? 'checked' : '' }} required>
                            <div class="text-center">
                                <i class="fas fa-car-side text-3xl text-sky-600"></i>
                                <p class="mt-3 font-bold text-slate-900">Mobil</p>
                                <p class="mt-1 text-xs text-slate-500">Untuk kendaraan roda empat</p>
                            </div>
                        </label>
                        <label class="vehicle-card cursor-pointer rounded-2xl border border-slate-200 p-4 transition hover:border-emerald-400 hover:bg-emerald-50">
                            <input type="radio" name="vehicle_type" value="motor" class="sr-only" {{ old('vehicle_type') === 'motor' ? 'checked' : '' }} required>
                            <div class="text-center">
                                <i class="fas fa-motorcycle text-3xl text-emerald-600"></i>
                                <p class="mt-3 font-bold text-slate-900">Motor</p>
                                <p class="mt-1 text-xs text-slate-500">Untuk kendaraan roda dua</p>
                            </div>
                        </label>
                        <label class="vehicle-card cursor-pointer rounded-2xl border border-slate-200 p-4 transition hover:border-amber-400 hover:bg-amber-50">
                            <input type="radio" name="vehicle_type" value="truk" class="sr-only" {{ old('vehicle_type') === 'truk' ? 'checked' : '' }} required>
                            <div class="text-center">
                                <i class="fas fa-truck text-3xl text-amber-600"></i>
                                <p class="mt-3 font-bold text-slate-900">Truk</p>
                                <p class="mt-1 text-xs text-slate-500">Untuk kendaraan besar</p>
                            </div>
                        </label>
                    </div>
                    <div id="selected-vehicle-preview" class="mt-4 hidden rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center gap-4">
                            <div id="selected-vehicle-icon" class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-900 text-2xl text-white"></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kendaraan Dipilih</p>
                                <p id="selected-vehicle-name" class="mt-1 text-xl font-black text-slate-900"></p>
                                <p id="selected-vehicle-description" class="mt-1 text-sm text-slate-500"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Spot Parkir</label>
                    <select
                        name="parking_spot_id"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                        required
                    >
                        <option value="">Pilih spot tersedia</option>
                        @foreach ($availableSpots as $spot)
                            <option value="{{ $spot->id }}" {{ (string) old('parking_spot_id') === (string) $spot->id ? 'selected' : '' }}>
                                {{ $spot->spot_number }} - Lantai {{ $spot->floor }} - Rp {{ number_format($spot->price_per_hour, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Foto Kendaraan Masuk</label>
                    <input
                        id="entry_photo"
                        type="file"
                        name="entry_photo"
                        accept="image/*"
                        class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600"
                        required
                    >
                    <div id="entry-photo-preview" class="mt-4 hidden overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <img id="entry-photo-preview-image" src="" alt="Preview foto kendaraan masuk" class="h-56 w-full object-cover">
                    </div>
                </div>

                <div class="rounded-2xl bg-amber-50 px-4 py-3 text-sm text-amber-700 ring-1 ring-amber-200">
                    Jam operasional parkir hanya sampai pukul 22.00 untuk proses masuk dan keluar. Tarif tetap mengikuti harga spot saat masuk dan tidak berubah saat keluar.
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-slate-900 px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-cyan-700">
                        Simpan Masuk
                    </button>
                    <a href="{{ route('payments.index') }}" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Lihat Laporan
                    </a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Kendaraan Sedang Parkir</h2>
                    <p class="mt-2 text-sm text-slate-500">Operator bisa lihat foto masuk, status batas waktu, dan upload foto saat kendaraan keluar.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $activeVehicles->count() }} aktif</span>
            </div>

            @forelse ($activeVehicles as $parking)
                <div class="mb-5 rounded-3xl border border-slate-200 p-5 last:mb-0">
                    <div class="grid gap-5 lg:grid-cols-[1fr,1.2fr]">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Foto masuk</p>
                            @if ($parking->entry_photo_url)
                                <img src="{{ $parking->entry_photo_url }}" alt="Foto kendaraan masuk {{ $parking->plate_number }}" class="mt-3 h-56 w-full rounded-2xl object-cover">
                            @else
                                <div class="mt-3 flex h-56 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">Foto belum tersedia</div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $parking->vehicle_type === 'mobil' ? 'bg-sky-100 text-sky-700' : ($parking->vehicle_type === 'truk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    @if ($parking->vehicle_type === 'mobil')
                                        <i class="fas fa-car-side text-xl"></i>
                                    @elseif ($parking->vehicle_type === 'truk')
                                        <i class="fas fa-truck text-xl"></i>
                                    @else
                                        <i class="fas fa-motorcycle text-xl"></i>
                                    @endif
                                </div>
                                <h3 class="text-2xl font-black text-slate-900">{{ $parking->plate_number }}</h3>
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $parking->vehicle_type === 'mobil' ? 'bg-sky-100 text-sky-700' : ($parking->vehicle_type === 'truk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ ucfirst($parking->vehicle_type) }}
                                </span>
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $parking->is_over_limit ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $parking->is_over_limit ? 'Lewat 22.00' : 'Masih dalam batas' }}
                                </span>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Spot</p>
                                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $parking->parkingSpot->spot_number ?? '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tarif tetap</p>
                                    <p class="mt-2 text-lg font-bold text-slate-900">Rp {{ number_format($parking->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Masuk</p>
                                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $parking->entry_time?->format('d M Y H:i') }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Batas keluar</p>
                                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $parking->max_exit_time?->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="rounded-2xl {{ $parking->is_over_limit ? 'bg-red-50 text-red-700 ring-1 ring-red-200' : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' }} px-4 py-3 text-sm">
                                Durasi saat ini {{ $parking->duration }} jam.
                                @if ($parking->is_over_limit)
                                    Kendaraan ini sudah melewati batas parkir pukul 22.00.
                                @else
                                    Kendaraan masih dalam batas parkir yang diizinkan.
                                @endif
                            </div>

                            <form method="POST" action="{{ route('parkings.exit', $parking) }}" enctype="multipart/form-data" class="space-y-3 rounded-2xl bg-slate-50 p-4">
                                @csrf
                                @method('DELETE')
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Foto Kendaraan Keluar</label>
                                    <input
                                        type="file"
                                        name="exit_photo"
                                        accept="image/*"
                                        class="block w-full rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm text-slate-600"
                                        required
                                    >
                                </div>
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                                    Proses Kendaraan Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-14 text-center text-slate-500">
                    Belum ada kendaraan aktif.
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-900">Laporan Parkir Admin</h2>
                <p class="mt-2 text-sm text-slate-500">Semua laporan harian, mingguan, dan bulanan sekarang digabung dalam satu halaman laporan.</p>
            </div>
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                Buka Menu Laporan
            </a>
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="mb-6">
            <h2 class="text-2xl font-black text-slate-900">Riwayat Keluar Terbaru</h2>
            <p class="mt-2 text-sm text-slate-500">Membandingkan foto kendaraan saat masuk dan keluar agar pemeriksaan lebih mudah.</p>
        </div>

        <div class="grid gap-5 lg:grid-cols-2 xl:grid-cols-3">
            @forelse ($recentCompleted as $parking)
                <div class="rounded-3xl border border-slate-200 p-5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $parking->vehicle_type === 'mobil' ? 'bg-sky-100 text-sky-700' : ($parking->vehicle_type === 'truk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    @if ($parking->vehicle_type === 'mobil')
                                        <i class="fas fa-car-side"></i>
                                    @elseif ($parking->vehicle_type === 'truk')
                                        <i class="fas fa-truck"></i>
                                    @else
                                        <i class="fas fa-motorcycle"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">{{ $parking->plate_number }}</h3>
                                    <p class="text-sm text-slate-500">{{ $parking->parkingSpot->spot_number ?? '-' }} • {{ ucfirst($parking->vehicle_type) }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $parking->is_over_limit ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $parking->is_over_limit ? 'Lewat batas' : 'Normal' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Masuk</p>
                            @if ($parking->entry_photo_url)
                                <img src="{{ $parking->entry_photo_url }}" alt="Foto masuk {{ $parking->plate_number }}" class="h-32 w-full rounded-2xl object-cover">
                            @else
                                <div class="flex h-32 items-center justify-center rounded-2xl bg-slate-100 text-xs text-slate-400">Tidak ada foto</div>
                            @endif
                        </div>
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Keluar</p>
                            @if ($parking->exit_photo_url)
                                <img src="{{ $parking->exit_photo_url }}" alt="Foto keluar {{ $parking->plate_number }}" class="h-32 w-full rounded-2xl object-cover">
                            @else
                                <div class="flex h-32 items-center justify-center rounded-2xl bg-slate-100 text-xs text-slate-400">Tidak ada foto</div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                        <p>Masuk: {{ $parking->entry_time?->format('d M Y H:i') }}</p>
                        <p class="mt-1">Keluar: {{ $parking->exit_time?->format('d M Y H:i') }}</p>
                        <p class="mt-1 font-semibold text-slate-900">Total bayar: Rp {{ number_format($parking->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-14 text-center text-slate-500 lg:col-span-2 xl:col-span-3">
                    Riwayat kendaraan keluar belum ada.
                </div>
            @endforelse
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const vehicleCards = document.querySelectorAll('.vehicle-card');
    const updateVehicleCards = () => {
        vehicleCards.forEach((card) => {
            const input = card.querySelector('input[type="radio"]');
            if (input?.checked) {
                card.classList.add('ring-2', 'ring-cyan-500', 'border-cyan-400', 'bg-cyan-50');
            } else {
                card.classList.remove('ring-2', 'ring-cyan-500', 'border-cyan-400', 'bg-cyan-50');
            }
        });
    };

    vehicleCards.forEach((card) => {
        card.addEventListener('click', updateVehicleCards);
    });

    updateVehicleCards();

    const photoInput = document.getElementById('entry_photo');
    const previewWrapper = document.getElementById('entry-photo-preview');
    const previewImage = document.getElementById('entry-photo-preview-image');
    const vehiclePreview = document.getElementById('selected-vehicle-preview');
    const vehicleIcon = document.getElementById('selected-vehicle-icon');
    const vehicleName = document.getElementById('selected-vehicle-name');
    const vehicleDescription = document.getElementById('selected-vehicle-description');
    const vehicleMeta = {
        mobil: {
            icon: '<i class="fas fa-car-side"></i>',
            name: 'Mobil',
            description: 'Kendaraan roda empat untuk area parkir mobil.',
        },
        motor: {
            icon: '<i class="fas fa-motorcycle"></i>',
            name: 'Motor',
            description: 'Kendaraan roda dua untuk parkir motor.',
        },
        truk: {
            icon: '<i class="fas fa-truck"></i>',
            name: 'Truk',
            description: 'Kendaraan besar yang membutuhkan area parkir lebih luas.',
        }
    };

    const updateSelectedVehiclePreview = () => {
        const selectedInput = document.querySelector('.vehicle-card input[type="radio"]:checked');
        if (!selectedInput || !vehiclePreview || !vehicleIcon || !vehicleName || !vehicleDescription) {
            vehiclePreview?.classList.add('hidden');
            return;
        }

        const meta = vehicleMeta[selectedInput.value];
        if (!meta) {
            vehiclePreview.classList.add('hidden');
            return;
        }

        vehicleIcon.innerHTML = meta.icon;
        vehicleName.textContent = meta.name;
        vehicleDescription.textContent = meta.description;
        vehiclePreview.classList.remove('hidden');
    };

    if (photoInput && previewWrapper && previewImage) {
        photoInput.addEventListener('change', (event) => {
            const [file] = event.target.files ?? [];
            if (!file) {
                previewWrapper.classList.add('hidden');
                previewImage.src = '';
                return;
            }

            previewImage.src = URL.createObjectURL(file);
            previewWrapper.classList.remove('hidden');
        });
    }

    vehicleCards.forEach((card) => {
        card.addEventListener('click', updateSelectedVehiclePreview);
    });

    updateSelectedVehiclePreview();
});
</script>
@endsection
