@extends('layouts.app')

@section('title', 'Laporan Parkir')

@section('content')
<div class="space-y-8">
    <style>
        @media print {
            .print-photo-cell {
                width: 88px;
            }

            .print-photo-image {
                width: 80px !important;
                height: 58px !important;
                border-radius: 12px !important;
            }

            .print-icon-box {
                width: 44px !important;
                height: 44px !important;
            }
        }
    </style>
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-sky-900 to-cyan-700 p-8 text-white shadow-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-100">Dashboard Admin</p>
                <h1 class="mt-3 text-4xl font-black">Laporan Parkir</h1>
                <p class="mt-3 max-w-3xl text-sm text-cyan-50">
                    Ringkasan laporan harian, mingguan, dan bulanan untuk kendaraan masuk, kendaraan keluar, pendapatan, petugas, dan pembayaran parkir.
                </p>
            </div>
            <div class="flex flex-wrap gap-3 print:hidden">
                <a href="{{ route('dashboard') }}" class="rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                    Kembali ke Dashboard
                </a>
                <button type="button" onclick="window.print()" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 shadow-sm">
            <p class="font-semibold">Filter laporan belum valid.</p>
            <ul class="mt-2 list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 print:shadow-none">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-900">Filter Laporan</h2>
                <p class="mt-2 text-sm text-slate-500">Gunakan filter untuk melihat detail tabel dan ringkasan tambahan pada periode tertentu.</p>
            </div>
            <div class="rounded-2xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">
                {{ $periodLabel }}: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <form method="GET" action="{{ route('laporan.index') }}" class="rounded-2xl border border-slate-200 p-5">
                <input type="hidden" name="type" value="daily">
                <label for="date" class="block text-sm font-semibold text-slate-700">Laporan Harian</label>
                <input
                    id="date"
                    type="date"
                    name="date"
                    value="{{ $type === 'daily' ? ($filters['date'] ?? '') : now()->toDateString() }}"
                    class="mt-3 w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                    required
                >
                <button type="submit" class="mt-4 w-full rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    Tampilkan Harian
                </button>
            </form>

            <form method="GET" action="{{ route('laporan.index') }}" class="rounded-2xl border border-slate-200 p-5">
                <input type="hidden" name="type" value="weekly">
                <label class="block text-sm font-semibold text-slate-700">Laporan Mingguan</label>
                <div class="mt-3 grid gap-3">
                    <input
                        type="date"
                        name="start_date"
                        value="{{ $type === 'weekly' ? ($filters['start_date'] ?? '') : now()->startOfWeek()->toDateString() }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        required
                    >
                    <input
                        type="date"
                        name="end_date"
                        value="{{ $type === 'weekly' ? ($filters['end_date'] ?? '') : now()->endOfWeek()->toDateString() }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        required
                    >
                </div>
                <button type="submit" class="mt-4 w-full rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    Tampilkan Mingguan
                </button>
            </form>

            <form method="GET" action="{{ route('laporan.index') }}" class="rounded-2xl border border-slate-200 p-5">
                <input type="hidden" name="type" value="monthly">
                <label class="block text-sm font-semibold text-slate-700">Laporan Bulanan</label>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <select
                        name="month"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        required
                    >
                        @for ($month = 1; $month <= 12; $month++)
                            <option value="{{ $month }}" {{ (int) ($type === 'monthly' ? ($filters['month'] ?? now()->month) : now()->month) === $month ? 'selected' : '' }}>
                                {{ $monthNames[$month] }}
                            </option>
                        @endfor
                    </select>
                    <input
                        type="number"
                        name="year"
                        value="{{ $type === 'monthly' ? ($filters['year'] ?? now()->year) : now()->year }}"
                        min="2000"
                        max="3000"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        required
                    >
                </div>
                <button type="submit" class="mt-4 w-full rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    Tampilkan Bulanan
                </button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-3xl bg-gradient-to-br from-sky-500 to-blue-700 p-6 text-white shadow-sm print:shadow-none">
            <p class="text-sm font-semibold uppercase tracking-wide text-sky-100">Laporan Harian</p>
            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-sky-100">Kendaraan masuk</span>
                    <span class="text-xl font-black">{{ $dailyReport['total_vehicles_in'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-sky-100">Kendaraan keluar</span>
                    <span class="text-xl font-black">{{ $dailyReport['total_vehicles_out'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-sky-100">Pendapatan</span>
                    <span class="text-xl font-black">Rp {{ number_format($dailyReport['total_revenue'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-green-700 p-6 text-white shadow-sm print:shadow-none">
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-100">Laporan Mingguan</p>
            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-emerald-100">Kendaraan masuk</span>
                    <span class="text-xl font-black">{{ $weeklyReport['total_vehicles_in'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-emerald-100">Kendaraan keluar</span>
                    <span class="text-xl font-black">{{ $weeklyReport['total_vehicles_out'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-emerald-100">Pendapatan</span>
                    <span class="text-xl font-black">Rp {{ number_format($weeklyReport['total_revenue'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="rounded-3xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 text-white shadow-sm print:shadow-none">
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-100">Laporan Bulanan</p>
            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-amber-100">Kendaraan masuk</span>
                    <span class="text-xl font-black">{{ $monthlyReport['total_vehicles_in'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-amber-100">Kendaraan keluar</span>
                    <span class="text-xl font-black">{{ $monthlyReport['total_vehicles_out'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-amber-100">Pendapatan</span>
                    <span class="text-xl font-black">Rp {{ number_format($monthlyReport['total_revenue'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 print:shadow-none">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-900">Ringkasan Periode Aktif</h2>
                <p class="mt-2 text-sm text-slate-500">Ringkasan berdasarkan filter yang sedang dipilih.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Kendaraan Masuk</p>
                    <p class="mt-3 text-3xl font-black text-slate-900">{{ $summary['total_vehicles_in'] }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Kendaraan Keluar</p>
                    <p class="mt-3 text-3xl font-black text-slate-900">{{ $summary['total_vehicles_out'] }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Pendapatan</p>
                    <p class="mt-3 text-3xl font-black text-slate-900">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 print:shadow-none">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-900">Tipe Kendaraan</h2>
                <p class="mt-2 text-sm text-slate-500">Data kendaraan berdasarkan periode aktif.</p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between rounded-2xl bg-sky-50 px-4 py-4">
                    <span class="font-semibold text-sky-800">Mobil</span>
                    <span class="text-2xl font-black text-sky-900">{{ $vehicleTypeSummary['mobil'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-emerald-50 px-4 py-4">
                    <span class="font-semibold text-emerald-800">Motor</span>
                    <span class="text-2xl font-black text-emerald-900">{{ $vehicleTypeSummary['motor'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-amber-50 px-4 py-4">
                    <span class="font-semibold text-amber-800">Truk</span>
                    <span class="text-2xl font-black text-amber-900">{{ $vehicleTypeSummary['truk'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[0.9fr,1.1fr]">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 print:shadow-none">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-900">Pembayaran Cash</h2>
                <p class="mt-2 text-sm text-slate-500">Rekap pembayaran cash pada periode aktif.</p>
            </div>

            <div class="grid gap-4">
                <div class="rounded-2xl bg-amber-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">Total Transaksi</p>
                    <p class="mt-3 text-3xl font-black text-amber-900">{{ $cashSummary['total_transactions'] }}</p>
                </div>
                <div class="rounded-2xl bg-orange-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-orange-700">Total Uang</p>
                    <p class="mt-3 text-3xl font-black text-orange-900">Rp {{ number_format($cashSummary['total_amount'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 print:shadow-none">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-900">Data Petugas</h2>
                <p class="mt-2 text-sm text-slate-500">Nama petugas dan total pendapatan yang ditangani.</p>
            </div>

            <div class="space-y-3">
                @forelse ($staffSummary as $staff)
                    <div class="rounded-2xl bg-slate-50 px-4 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-bold text-slate-900">{{ $staff['name'] }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $staff['total_handled'] }} transaksi</p>
                            </div>
                            <p class="text-lg font-black text-slate-900">Rp {{ number_format($staff['total_revenue'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-50 px-4 py-6 text-sm text-slate-500">
                        Belum ada data petugas pada periode ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 print:shadow-none">
        <div class="mb-6">
            <h2 class="text-2xl font-black text-slate-900">Riwayat Parkir Terbaru</h2>
            <p class="mt-2 text-sm text-slate-500">Daftar kendaraan parkir terbaru sesuai periode aktif.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-slate-600">
                <thead>
                    <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Plat Nomor</th>
                        <th class="px-4 py-3">Gambar Kendaraan</th>
                        <th class="px-4 py-3">Tipe Kendaraan</th>
                        <th class="px-4 py-3">Foto Masuk</th>
                        <th class="px-4 py-3">Waktu Masuk</th>
                        <th class="px-4 py-3">Waktu Keluar</th>
                        <th class="px-4 py-3">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentParkingHistory as $item)
                        <tr class="border-b border-slate-100">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $item->vehicle_number ?? $item->plate_number ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="print-icon-box flex h-14 w-14 items-center justify-center rounded-2xl {{ ($item->vehicle_type ?? '') === 'mobil' ? 'bg-sky-100 text-sky-700' : (($item->vehicle_type ?? '') === 'truk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    @if (($item->vehicle_type ?? '') === 'mobil')
                                        <i class="fas fa-car-side text-2xl"></i>
                                    @elseif (($item->vehicle_type ?? '') === 'truk')
                                        <i class="fas fa-truck text-2xl"></i>
                                    @else
                                        <i class="fas fa-motorcycle text-2xl"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ ($item->vehicle_type ?? '') === 'mobil' ? 'bg-sky-100 text-sky-700' : (($item->vehicle_type ?? '') === 'truk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ ucfirst($item->vehicle_type ?? '-') }}
                                </span>
                            </td>
                            <td class="print-photo-cell px-4 py-3">
                                @if ($item->entry_photo_url)
                                    <img src="{{ $item->entry_photo_url }}" alt="Foto masuk {{ $item->vehicle_number ?? $item->plate_number ?? '-' }}" class="print-photo-image h-20 w-28 rounded-2xl object-cover ring-1 ring-slate-200">
                                @else
                                    <div class="print-photo-image flex h-20 w-28 items-center justify-center rounded-2xl bg-slate-100 text-xs text-slate-400 ring-1 ring-slate-200">
                                        Tidak ada foto
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ optional($item->check_in ?? $item->entry_time)->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">{{ optional($item->check_out ?? $item->exit_time)->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                Belum ada riwayat parkir pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 print:shadow-none">
        <div class="mb-6">
            <h2 class="text-2xl font-black text-slate-900">Riwayat Pembayaran</h2>
            <p class="mt-2 text-sm text-slate-500">Daftar pembayaran terbaru beserta petugas yang menangani.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-slate-600">
                <thead>
                    <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Nama Petugas</th>
                        <th class="px-4 py-3">Plat Nomor</th>
                        <th class="px-4 py-3">Metode Pembayaran</th>
                        <th class="px-4 py-3">Jumlah Bayar</th>
                        <th class="px-4 py-3">Waktu Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentHistory as $payment)
                        <tr class="border-b border-slate-100">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $payment['staff_name'] }}</td>
                            <td class="px-4 py-3">{{ $payment['plate_number'] }}</td>
                            <td class="px-4 py-3">{{ ucfirst($payment['payment_method']) }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">Rp {{ number_format($payment['total_bayar'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ optional($payment['paid_at'])->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                Belum ada riwayat pembayaran pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
