@extends('layouts.app')

@section('title', 'Payments - Parking System')
<!-- Legacy template - redirect to new -->
<script>
window.location.href = '/payments';
</script>

@section('content')
<div class="space-y-10">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-5xl font-black mb-2">Payment Reports</h1>
            <p class="text-2xl text-gray-400">Complete transaction tracking and analytics</p>
        </div>
        <div class="flex gap-4">
            <button class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold shadow-xl">
                Export CSV
            </button>
            <button class="px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-bold shadow-xl">
                Generate Report
            </button>
        </div>
    </div>

    <!-- Revenue Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card p-12 text-center">
            <i class="fas fa-dollar-sign text-6xl text-emerald-400 mb-6"></i>
            <p class="text-6xl font-black">${{ number_format(App\Models\Payment::where('status', 'paid')->sum('amount'), 2) }}</p>
            <p class="text-2xl font-bold text-gray-400 mt-4">Total Paid</p>
        </div>
        <div class="card p-12 text-center">
            <i class="fas fa-clock text-6xl text-orange-400 mb-6"></i>
            <p class="text-6xl font-black">${{ number_format(App\Models\Payment::where('status', 'pending')->sum('amount'), 2) }}</p>
            <p class="text-2xl font-bold text-gray-400 mt-4">Pending</p>
        </div>
        <div class="card p-12 text-center">
            <i class="fas fa-chart-line text-6xl text-blue-400 mb-6"></i>
            <p class="text-6xl font-black">{{ App\Models\Payment::count() }}</p>
            <p class="text-2xl font-bold text-gray-400 mt-4">Transactions</p>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Payment ID</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Reservation</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Amount</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Status</th>
                        <th class="text-left py-8 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Method</th>
                        <th class="text-left py-8 font-bold text-xl uppercase tracking-wider text-gray-300">Date</th>
                        <th class="text-left py-8 font-bold text-xl uppercase tracking-wider text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach(App\Models\Payment::with('reservation.user')->latest()->paginate(10) as $payment)
                    <tr class="hover:bg-white/5 transition-all">
                        <td class="py-8 pr-8">
                            <div class="font-mono text-xl font-bold text-blue-400">#{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="py-8 pr-8">
                            <div class="font-bold text-lg">#{{ str_pad($payment->reservation_id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-sm text-gray-400">{{ $payment->reservation->user->name }}</div>
                        </td>
                        <td class="py-8 pr-8">
                            <p class="text-3xl font-black text-emerald-400">${{ number_format($payment->amount, 2) }}</p>
                        </td>
                        <td class="py-8 pr-8">
                            <span class="status-badge status-{{ $payment->status }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="py-8 pr-8">
                            <span class="inline-flex items-center gap-2 px-6 py-3 bg-gray-500/20 border border-gray-500/50 rounded-2xl text-gray-300 font-semibold">
                                <i class="fab fa-cc-{{ strtolower($payment->payment_method) }}"></i>
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td class="py-8 pr-8">
                            <p class="text-lg font-mono">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                            <p class="text-sm text-gray-400">{{ $payment->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="py-8">
                            <div class="flex gap-3">
                                <button class="btn btn-emerald px-6 py-3 text-sm shadow-lg">
                                    <i class="fas fa-receipt mr-2"></i>Invoice
                                </button>
                                <button class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-3 text-sm rounded-2xl font-semibold text-white shadow-lg">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-12 flex justify-center">
            {{ App\Models\Payment::with('reservation.admin')->latest()->paginate(10)->links() }}
        </div>
    </div>
</div>
@endsection

