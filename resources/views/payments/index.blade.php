@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <div>
<h1 class="text-4xl font-bold mb-2">Laporan Pembayaran</h1>
            <p class="text-xl text-gray-600">Laporan lengkap transaksi parkir & pembayaran</p>
        </div>
        <div class="flex gap-4">
            <button onclick="exportCSV()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition-all">
                <i class="fas fa-download mr-2"></i>
                Export CSV
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-green-800 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-2xl p-8 shadow-2xl">
            <div class="text-4xl mb-2 font-bold">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            <p class="text-lg opacity-90">Total Dibayar</p>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-2xl p-8 shadow-2xl">
            <div class="text-4xl mb-2 font-bold">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
            <p class="text-lg opacity-90">Menunggu</p>
        </div>
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl p-8 shadow-2xl">
            <div class="text-4xl mb-2 font-bold">{{ $totalTransactions }}</div>
            <p class="text-lg opacity-90">Jumlah Transaksi</p>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 p-8">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-4 font-bold text-lg">ID</th>
                        <th class="text-left py-4 font-bold text-lg">Plat</th>
                        <th class="text-left py-4 font-bold text-lg">Masuk</th>
                        <th class="text-left py-4 font-bold text-lg">Keluar</th>
                        <th class="text-left py-4 font-bold text-lg">Durasi</th>
                        <th class="text-left py-4 font-bold text-lg">Total</th>
                        <th class="text-left py-4 font-bold text-lg">Status</th>
                        <th class="text-left py-4 font-bold text-lg">Metode</th>
                        <th class="text-left py-4 font-bold text-lg">Tanggal</th>
                        <th class="text-left py-4 font-bold text-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-all border-b border-gray-100">
                        <td class="py-4 font-mono font-bold">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-4 font-bold text-lg">{{ $payment->plate_number }}</td>
                        <td class="py-4">{{ $payment->entry_time ? $payment->entry_time->format('d/m H:i') : '-' }}</td>
                        <td class="py-4">{{ $payment->exit_time ? $payment->exit_time->format('d/m H:i') : '-' }}</td>
                        <td class="py-4 font-bold">{{ $payment->duration ?? 0 }} jam</td>
                        <td class="py-4 font-bold text-xl text-green-600">Rp {{ number_format($payment->total_amount ?? 0, 0, ',', '.') }}</td>
                        <td class="py-4">
                        <span class="px-4 py-2 rounded-full text-sm font-bold {{ ($payment->status ?? 'pending') == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">

                                {{ ($payment->status ?? 'pending') == 'paid' ? 'Berhasil' : 'Menunggu' }}

                            </span>
                        </td>
                        <td class="py-4">{{ $payment->payment_method ?: 'Cash' }}</td>
                        <td class="py-4">{{ $payment->created_at->format('d/m/Y') }}</td>
                        <td class="py-4">
                            @if(($payment->status ?? 'pending') === 'paid')
                                <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                    Cetak
                                </a>
                            @else
                                <span class="px-4 py-2 rounded-xl text-sm bg-gray-200 text-gray-600">Belum Bayar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-12 text-gray-500">
                            <i class="fas fa-receipt text-4xl mb-4 block"></i>
                            Belum ada data pembayaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="closeDetail()">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold">Detail Transaksi</h2>
                    <button onclick="closeDetail()" class="text-2xl">&times;</button>
                </div>
                <div id="detailContent">
                    <!-- Content loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPaymentId = null;

function showDetail(id) {
    currentPaymentId = id;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = `
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i>
            </div>
            <p class="text-xl text-gray-500">Loading detail...</p>
        </div>
    `;
    // Simulate AJAX load
    setTimeout(() => {
        document.getElementById('detailContent').innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-center md:text-left">
                <div>
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-2xl mb-6">
                        <div class="text-4xl font-bold mb-1">Rp 25.000</div>
                        <p class="opacity-90">Total Bayar</p>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span>Plat Kendaraan</span>
                            <span class="font-bold">B 1234 XYZ</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tarif / Jam</span>
                            <span class="font-bold">Rp 5.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Durasi Parkir</span>
                            <span class="font-bold">5 jam</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span>Jam Masuk</span>
                            <span class="font-bold">14:30, 16 Apr</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jam Keluar</span>
                            <span class="font-bold">19:30, 16 Apr</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status</span>
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold">Paid</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Metode Bayar</span>
                            <span class="font-bold">Cash</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 500);
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
}

function exportCSV() {
    let csvContent = [];
    csvContent.push(['ID','Plat','Masuk','Keluar','Durasi','Total','Status','Metode','Tanggal']);
    @foreach($payments as $payment)
    csvContent.push(['{{ $payment->id }}','{{ $payment->plate_number }}','{{ $payment->entry_time }}','{{ $payment->exit_time }}','{{ $payment->duration }} jam','Rp {{ number_format($payment->total_amount, 0, ",", ".") }}','{{ $payment->status }}','{{ $payment->payment_method ?: "Cash" }}','{{ $payment->created_at }}']);
    @endforeach
    let csv = csvContent.map(row => row.map(cell => `"${cell}"`).join(',')).join('\\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'payments.csv';
    a.click();
}
</script>

@endsection

