@extends('layouts.app')

@section('title', 'Reservasi Spot Parkir')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="mb-10">
        <h1 class="text-4xl font-bold mb-2">Reservasi Spot Parkir</h1>
        <p class="text-gray-600">Pilih lantai, pilih spot, lalu pesan parkir dengan harga berdasarkan lantai.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[320px_1fr] mb-10">
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Filter Lantai</h2>
            <form action="{{ route('reservations.create') }}" method="GET">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Lantai</label>
                <select name="floor" onchange="this.form.submit()" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="1" {{ $floor == 1 ? 'selected' : '' }}>Lantai 1</option>
                    <option value="2" {{ $floor == 2 ? 'selected' : '' }}>Lantai 2</option>
                    <option value="3" {{ $floor == 3 ? 'selected' : '' }}>Lantai 3</option>
                </select>
            </form>

            <div class="mt-8">
                <p class="text-sm text-gray-600">Harga per jam:</p>
                <ul class="mt-4 space-y-3 text-sm text-gray-700">
                    <li><span class="font-semibold">Lantai 1 (A*)</span>: Rp 5.000</li>
                    <li><span class="font-semibold">Lantai 2 (B*)</span>: Rp 4.000</li>
                    <li><span class="font-semibold">Lantai 3 (C*)</span>: Rp 3.000</li>
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Pilih Spot Parkir</h2>
            <div class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm text-blue-900">
                <span class="font-semibold">Lantai {{ $floor }}</span> hanya menampilkan spot yang tersedia.
                Pilih spot untuk menghitung total harga sesuai durasi.
            </div>
            <form method="POST" action="{{ route('reservations.store') }}">
                @csrf

                <div class="grid gap-6 md:grid-cols-3 mb-8">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Parkir (jam)</label>
                        <input type="number" name="duration" value="{{ old('duration', 1) }}" min="1" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('duration')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Total Harga</label>
                        <div id="totalPrice" class="w-full p-4 rounded-xl bg-gray-50 border border-gray-200 text-lg font-bold text-gray-900">Rp 0</div>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2 mb-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Plat</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="B 1234 XYZ" required>
                        @error('plate_number')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kendaraan</label>
                        <select name="vehicle_type" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Jenis</option>
                            <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Mobil</option>
                            <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motor</option>
                            <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truk</option>
                        </select>
                        @error('vehicle_type')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <input type="hidden" name="parking_spot_id" id="parkingSpotId" value="{{ old('parking_spot_id') }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    @forelse($spots as $spot)
                        <div class="spot-card rounded-3xl border border-emerald-200 bg-emerald-50 p-6 transition shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-2xl font-bold">{{ $spot->spot_number }}</p>
                                    <p class="text-sm text-gray-600">Lantai {{ $spot->floor }}</p>
                                </div>
                                <span class="px-3 py-2 rounded-full text-sm font-semibold bg-emerald-500/20 text-emerald-800">
                                    Tersedia
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Harga per jam: Rp {{ number_format($spot->price_per_hour, 0, ',', '.') }}</p>
                            <button type="button" class="reserveButton w-full py-3 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition" data-spot-id="{{ $spot->id }}" data-price="{{ $spot->price_per_hour }}">
                                Pilih Spot
                            </button>
                        </div>
                    @empty
                        <div class="md:col-span-3 p-8 rounded-3xl border border-gray-200 bg-white text-center">
                            <p class="text-lg font-semibold text-gray-700">Tidak ada spot tersedia di lantai ini.</p>
                            <p class="text-gray-500 mt-2">Silakan pilih lantai lain atau tunggu sampai ada kendaraan keluar.</p>
                        </div>
                    @endforelse
                </div>

                @error('parking_spot_id')
                    <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-6">
                    <button type="submit" class="w-full py-4 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">Reserve Spot</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const durationInput = document.querySelector('input[name="duration"]');
    const totalPriceContainer = document.getElementById('totalPrice');
    const parkingSpotIdInput = document.getElementById('parkingSpotId');
    let selectedPrice = 0;

    function updateTotal() {
        const duration = parseInt(durationInput.value) || 0;
        const total = selectedPrice * duration;
        totalPriceContainer.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    document.querySelectorAll('.reserveButton').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.reserveButton').forEach(btn => {
                btn.textContent = 'Pilih Spot';
                btn.closest('.spot-card')?.classList.remove('ring-2', 'ring-blue-500', 'scale-[1.02]');
            });
            button.textContent = 'Terpilih';
            button.closest('.spot-card')?.classList.add('ring-2', 'ring-blue-500', 'scale-[1.02]');
            parkingSpotIdInput.value = button.dataset.spotId;
            selectedPrice = parseInt(button.dataset.price, 10);
            updateTotal();
        });
    });

    durationInput.addEventListener('input', updateTotal);
    updateTotal();
</script>
@endsection
