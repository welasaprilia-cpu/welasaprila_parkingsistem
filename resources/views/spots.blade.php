@extends('layouts.app')

@section('title', 'Parkings')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-2xl p-8 text-white flex flex-col lg:flex-row justify-between items-center mb-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-white text-pink-600 rounded-xl flex items-center justify-center text-3xl font-bold">
                P
            </div>

            <div>
                <h1 class="text-4xl font-bold">Sistem Parkir Digital</h1>
                <p class="text-pink-100 mt-2">
                    Kelola spot parkir dan lihat foto kendaraan masuk atau keluar dengan mudah.
                </p>
            </div>
        </div>

        <div class="mt-6 lg:mt-0 border border-white/30 rounded-xl p-6 text-center w-72">
            <p class="text-2xl font-bold">Tarif Tetap Rp {{ number_format($ratePerHour ?? 5000, 0, ',', '.') }}</p>
            <p class="text-sm text-pink-100">Batas parkir sampai pukul 22.00</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-md">
            <div class="bg-gradient-to-r from-pink-400 to-pink-500 text-white px-6 py-3 rounded-t-2xl font-semibold">
                Entry Kendaraan Baru
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('parking.store') }}" enctype="multipart/form-data">
                    @csrf

                    <label class="block text-gray-700 mb-2">Nomor Plat Kendaraan</label>
                    <input
                        type="text"
                        name="plate_number"
                        value="{{ old('plate_number') }}"
                        class="w-full border rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-pink-500"
                        placeholder="B 1234 XYZ"
                        required
                    >

                    <label class="block text-gray-700 mb-2">Jenis Kendaraan</label>
                    <div class="grid grid-cols-1 gap-3 mb-4 sm:grid-cols-3">
                        <label class="spot-vehicle-card cursor-pointer rounded-xl border border-gray-200 p-4 transition hover:border-pink-400 hover:bg-pink-50">
                            <input type="radio" name="vehicle_type" value="mobil" class="sr-only" {{ old('vehicle_type') == 'mobil' ? 'checked' : '' }} required>
                            <div class="text-center">
                                <i class="fas fa-car-side text-3xl text-pink-600"></i>
                                <p class="mt-2 font-bold text-gray-900">Mobil</p>
                            </div>
                        </label>
                        <label class="spot-vehicle-card cursor-pointer rounded-xl border border-gray-200 p-4 transition hover:border-pink-400 hover:bg-pink-50">
                            <input type="radio" name="vehicle_type" value="motor" class="sr-only" {{ old('vehicle_type') == 'motor' ? 'checked' : '' }} required>
                            <div class="text-center">
                                <i class="fas fa-motorcycle text-3xl text-emerald-600"></i>
                                <p class="mt-2 font-bold text-gray-900">Motor</p>
                            </div>
                        </label>
                        <label class="spot-vehicle-card cursor-pointer rounded-xl border border-gray-200 p-4 transition hover:border-pink-400 hover:bg-pink-50">
                            <input type="radio" name="vehicle_type" value="truk" class="sr-only" {{ old('vehicle_type') == 'truk' ? 'checked' : '' }} required>
                            <div class="text-center">
                                <i class="fas fa-truck text-3xl text-amber-600"></i>
                                <p class="mt-2 font-bold text-gray-900">Truk</p>
                            </div>
                        </label>
                    </div>
                    <div id="spot-selected-vehicle-preview" class="mb-4 hidden rounded-xl border border-pink-100 bg-pink-50 p-4">
                        <div class="flex items-center gap-4">
                            <div id="spot-selected-vehicle-icon" class="flex h-16 w-16 items-center justify-center rounded-2xl bg-pink-600 text-2xl text-white"></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-pink-500">Kendaraan Dipilih</p>
                                <p id="spot-selected-vehicle-name" class="mt-1 text-xl font-black text-gray-900"></p>
                                <p id="spot-selected-vehicle-description" class="mt-1 text-sm text-gray-500"></p>
                            </div>
                        </div>
                    </div>

                    <label class="block text-gray-700 mb-2">Pilih Spot Parkir</label>
                    <select name="parking_spot_id" class="w-full border rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <option value="">Pilih Spot Tersedia</option>
                        @foreach(($availableSpots ?? collect()) as $spot)
                            <option value="{{ $spot->id }}" {{ (string) old('parking_spot_id') === (string) $spot->id ? 'selected' : '' }}>
                                {{ $spot->spot_number }} - Lantai {{ $spot->floor }} - Tarif Rp {{ number_format($spot->price_per_hour, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>

                    <label class="block text-gray-700 mb-2">Foto Kendaraan Masuk</label>
                    <input
                        id="spot_entry_photo"
                        type="file"
                        name="entry_photo"
                        accept="image/*"
                        class="w-full border rounded-lg px-4 py-3 mb-4 bg-white focus:outline-none focus:ring-2 focus:ring-pink-500"
                        required
                    >
                    <div id="spot-entry-photo-preview" class="mb-4 hidden overflow-hidden rounded-xl border border-pink-100 bg-pink-50">
                        <img id="spot-entry-photo-preview-image" src="" alt="Preview foto kendaraan masuk" class="h-56 w-full object-cover">
                    </div>

                    <div class="bg-amber-50 text-amber-700 p-4 rounded-lg mb-4 text-sm">
                        Tarif parkir tetap mengikuti spot saat masuk dan tidak berubah meskipun kendaraan parkir lebih lama. Proses masuk dan keluar kendaraan hanya dilayani sampai pukul 22.00.
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                        Pilih Spot & Parkir Masuk
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-gradient-to-r from-pink-400 to-pink-500 rounded-2xl p-6 text-white flex justify-around items-center">
            <div class="text-center">
                <p class="text-3xl font-bold">{{ $activeCount }}</p>
                <p class="text-sm">Aktif</p>
            </div>

            <div class="h-12 border-l border-white/50"></div>

            <div class="text-center">
                <p class="text-3xl font-bold">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
                <p class="text-sm">Total Pendapatan</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold">Daftar Spot Parkir</h2>
            <p class="text-sm text-gray-500">Tampilan per lantai A1-A8, B1-B8, C1-C8</p>
        </div>

        <div class="space-y-6">
            @foreach(($spotsByFloor ?? collect()) as $floor => $floorSpots)
                <div class="rounded-2xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Lantai {{ $floor }}</h3>
                            <p class="text-sm text-gray-500">{{ $floorSpots->count() }} spot</p>
                        </div>
                        <div class="text-sm text-gray-600">
                            Tersedia: {{ $floorSpots->where('is_available', true)->count() }} / {{ $floorSpots->count() }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4 lg:grid-cols-8">
                        @foreach($floorSpots as $spot)
                            <div class="rounded-xl border px-4 py-3 text-center {{ $spot->is_available ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-700' }}">
                                <p class="font-bold">{{ $spot->spot_number }}</p>
                                <p class="mt-1 text-xs">{{ $spot->is_available ? 'Tersedia' : 'Terisi' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const vehicleCards = document.querySelectorAll('.spot-vehicle-card');
    const refreshCards = () => {
        vehicleCards.forEach((card) => {
            const input = card.querySelector('input[type="radio"]');
            if (input?.checked) {
                card.classList.add('ring-2', 'ring-pink-400', 'border-pink-400', 'bg-pink-50');
            } else {
                card.classList.remove('ring-2', 'ring-pink-400', 'border-pink-400', 'bg-pink-50');
            }
        });
    };

    vehicleCards.forEach((card) => card.addEventListener('click', refreshCards));
    refreshCards();

    const photoInput = document.getElementById('spot_entry_photo');
    const previewWrapper = document.getElementById('spot-entry-photo-preview');
    const previewImage = document.getElementById('spot-entry-photo-preview-image');
    const selectedVehiclePreview = document.getElementById('spot-selected-vehicle-preview');
    const selectedVehicleIcon = document.getElementById('spot-selected-vehicle-icon');
    const selectedVehicleName = document.getElementById('spot-selected-vehicle-name');
    const selectedVehicleDescription = document.getElementById('spot-selected-vehicle-description');
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

    const updateSelectedVehicle = () => {
        const selectedInput = document.querySelector('.spot-vehicle-card input[type="radio"]:checked');
        if (!selectedInput || !selectedVehiclePreview || !selectedVehicleIcon || !selectedVehicleName || !selectedVehicleDescription) {
            selectedVehiclePreview?.classList.add('hidden');
            return;
        }

        const meta = vehicleMeta[selectedInput.value];
        if (!meta) {
            selectedVehiclePreview.classList.add('hidden');
            return;
        }

        selectedVehicleIcon.innerHTML = meta.icon;
        selectedVehicleName.textContent = meta.name;
        selectedVehicleDescription.textContent = meta.description;
        selectedVehiclePreview.classList.remove('hidden');
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

    vehicleCards.forEach((card) => card.addEventListener('click', updateSelectedVehicle));
    updateSelectedVehicle();
});
</script>
@endsection
