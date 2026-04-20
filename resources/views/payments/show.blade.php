@extends('layouts.app')

@section('title', 'Invoice Pembayaran #' . str_pad($payment->id, 4, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-4xl font-bold">Invoice Pembayaran</h1>
            <p class="text-gray-600">Transaksi parkir nomor #{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="no-print flex gap-3">
            <a href="{{ route('payments.index') }}" class="px-5 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100">Kembali</a>
            <button type="button" onclick="window.print()" class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Cetak</button>
        </div>
    </div>

    <div class="bg-white p-10 rounded-3xl shadow-2xl border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div>
                <h2 class="text-xl font-semibold mb-4">Detail Pembayaran</h2>
                <div class="space-y-4 text-gray-700">
                    <div class="flex justify-between">
                        <span>ID Transaksi</span>
                        <span class="font-semibold">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Plat Kendaraan</span>
                        <span class="font-semibold">{{ $payment->plate_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jam Masuk</span>
                        <span class="font-semibold">{{ $payment->entry_time ? $payment->entry_time->format('d M Y H:i') : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jam Keluar</span>
                        <span class="font-semibold">{{ $payment->exit_time ? $payment->exit_time->format('d M Y H:i') : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Durasi</span>
                        <span class="font-semibold">{{ $payment->duration ?? 0 }} jam</span>
                    </div>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold mb-4">Status Pembayaran</h2>
                <div class="space-y-4 text-gray-700">
                    <div class="flex justify-between">
                        <span>Status</span>
{{ ($payment->status ?? 'pending') == 'paid' ? 'Berhasil' : 'Menunggu' }}
                    </div>
                    <div class="flex justify-between">
                        <span>Metode Bayar</span>
                        <span class="font-semibold">{{ $payment->payment_method ?: 'Cash' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal Transaksi</span>
                        <span class="font-semibold">{{ $payment->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-3xl p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-semibold">Ringkasan Pembayaran</h3>
                    <p class="text-sm text-gray-500">Periksa rincian sebelum mencetak.</p>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500">Invoice</span>
                    <div class="text-3xl font-black text-emerald-600">Rp {{ number_format($payment->total_amount ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700">
                <div class="rounded-2xl border border-gray-200 p-6">
                    <p class="text-sm uppercase tracking-wide text-gray-500 mb-2">Harga/jam</p>
                    <p class="text-xl font-semibold">Rp 5.000</p>
                </div>
                <div class="rounded-2xl border border-gray-200 p-6">
                    <p class="text-sm uppercase tracking-wide text-gray-500 mb-2">Total jam</p>
                    <p class="text-xl font-semibold">{{ $payment->duration ?? 0 }} jam</p>
                </div>
                <div class="rounded-2xl border border-gray-200 p-6">
                    <p class="text-sm uppercase tracking-wide text-gray-500 mb-2">Total Bayar</p>
                    <p class="text-xl font-semibold">Rp {{ number_format($payment->total_amount ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
}
</style>
@endsection
