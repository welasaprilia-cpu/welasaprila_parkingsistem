<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    private const MONTH_NAMES = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function index(Request $request)
    {
        $type = $request->query('type', 'daily');
        $filters = $this->prepareFilters($request, $type);
        $filters = $this->validateFilters($filters);
        [$startDate, $endDate, $periodLabel] = $this->resolvePeriod($filters['type'], $filters);

        $dailyReport = $this->dailyReport();
        $weeklyReport = $this->weeklyReport();
        $monthlyReport = $this->monthlyReport($filters);
        $summary = $this->buildSummary($startDate, $endDate);
        $vehicleTypeSummary = $this->buildVehicleTypeSummary($startDate, $endDate);
        $cashSummary = $this->buildCashSummary($startDate, $endDate);
        $staffSummary = $this->buildStaffSummary($startDate, $endDate);
        $recentParkingHistory = $this->buildRecentParkingHistory($startDate, $endDate);
        $paymentHistory = $this->buildPaymentHistory($startDate, $endDate);

        return view('laporan', [
            'type' => $filters['type'],
            'filters' => $filters,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => $summary,
            'dailyReport' => $dailyReport,
            'weeklyReport' => $weeklyReport,
            'monthlyReport' => $monthlyReport,
            'vehicleTypeSummary' => $vehicleTypeSummary,
            'cashSummary' => $cashSummary,
            'staffSummary' => $staffSummary,
            'recentParkingHistory' => $recentParkingHistory,
            'paymentHistory' => $paymentHistory,
            'monthNames' => self::MONTH_NAMES,
        ]);
    }

    public function dailyReport(): array
    {
        return $this->buildSummary(now()->startOfDay(), now()->endOfDay());
    }

    public function weeklyReport(): array
    {
        return $this->buildSummary(now()->startOfWeek(), now()->endOfWeek());
    }

    public function monthlyReport(array $filters = []): array
    {
        $month = (int) ($filters['month'] ?? now()->month);
        $year = (int) ($filters['year'] ?? now()->year);
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return $this->buildSummary($start, $end);
    }

    private function prepareFilters(Request $request, string $type): array
    {
        $type = in_array($type, ['daily', 'weekly', 'monthly'], true) ? $type : 'daily';

        return [
            'type' => $type,
            'date' => $request->query('date', now()->toDateString()),
            'start_date' => $request->query('start_date', now()->startOfWeek()->toDateString()),
            'end_date' => $request->query('end_date', now()->endOfWeek()->toDateString()),
            'month' => $request->query('month', now()->month),
            'year' => $request->query('year', now()->year),
        ];
    }

    private function validateFilters(array $filters): array
    {
        $validator = Validator::make(
            $filters,
            [
                'type' => 'required|in:daily,weekly,monthly',
                'date' => 'required_if:type,daily|date',
                'start_date' => 'required_if:type,weekly|date',
                'end_date' => 'required_if:type,weekly|date|after_or_equal:start_date',
                'month' => 'required_if:type,monthly|integer|between:1,12',
                'year' => 'required_if:type,monthly|integer|min:2000|max:3000',
            ],
            [
                'date.required_if' => 'Tanggal harian wajib diisi.',
                'date.date' => 'Format tanggal harian tidak valid.',
                'start_date.required_if' => 'Tanggal mulai wajib diisi untuk laporan mingguan.',
                'start_date.date' => 'Format tanggal mulai tidak valid.',
                'end_date.required_if' => 'Tanggal akhir wajib diisi untuk laporan mingguan.',
                'end_date.date' => 'Format tanggal akhir tidak valid.',
                'end_date.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
                'month.required_if' => 'Bulan wajib dipilih untuk laporan bulanan.',
                'year.required_if' => 'Tahun wajib diisi untuk laporan bulanan.',
            ]
        );

        $validator->after(function ($validator) use ($filters) {
            if (($filters['type'] ?? null) !== 'weekly') {
                return;
            }

            $start = Carbon::parse($filters['start_date']);
            $end = Carbon::parse($filters['end_date']);

            if ($start->diffInDays($end) > 6) {
                $validator->errors()->add('end_date', 'Range laporan mingguan maksimal 7 hari.');
            }
        });

        return $validator->validate();
    }

    private function resolvePeriod(string $type, array $filters): array
    {
        return match ($type) {
            'weekly' => [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay(),
                'Laporan Mingguan',
            ],
            'monthly' => [
                Carbon::createFromDate((int) $filters['year'], (int) $filters['month'], 1)->startOfMonth(),
                Carbon::createFromDate((int) $filters['year'], (int) $filters['month'], 1)->endOfMonth(),
                'Laporan Bulanan',
            ],
            default => [
                Carbon::parse($filters['date'])->startOfDay(),
                Carbon::parse($filters['date'])->endOfDay(),
                'Laporan Harian',
            ],
        };
    }

    private function buildSummary(Carbon $startDate, Carbon $endDate): array
    {
        $totalVehiclesIn = Parking::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalVehiclesOut = Parking::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('check_out')
            ->count();

        $totalRevenue = Payment::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_bayar');

        return [
            'total_vehicles_in' => $totalVehiclesIn,
            'total_vehicles_out' => $totalVehiclesOut,
            'total_revenue' => $totalRevenue,
        ];
    }

    private function buildVehicleTypeSummary(Carbon $startDate, Carbon $endDate): array
    {
        $baseQuery = Parking::query()->whereBetween('created_at', [$startDate, $endDate]);

        return [
            'mobil' => (clone $baseQuery)->where('vehicle_type', 'mobil')->count(),
            'motor' => (clone $baseQuery)->where('vehicle_type', 'motor')->count(),
            'truk' => (clone $baseQuery)->where('vehicle_type', 'truk')->count(),
        ];
    }

    private function buildCashSummary(Carbon $startDate, Carbon $endDate): array
    {
        $cashPayments = Payment::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('payment_method', 'cash')
                    ->orWhereNull('payment_method');
            });

        return [
            'total_transactions' => (clone $cashPayments)->count(),
            'total_amount' => (clone $cashPayments)->sum('total_bayar'),
        ];
    }

    private function buildStaffSummary(Carbon $startDate, Carbon $endDate): Collection
    {
        return Reservation::query()
            ->with('user')
            ->where('source', 'parking')
            ->whereBetween('reserved_at', [$startDate, $endDate])
            ->get()
            ->groupBy('user_id')
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'name' => $first?->user?->name ?? 'Petugas tidak diketahui',
                    'total_handled' => $items->count(),
                    'total_revenue' => (float) $items->sum('total_price'),
                ];
            })
            ->sortByDesc('total_revenue')
            ->values()
            ->take(5);
    }

    private function buildRecentParkingHistory(Carbon $startDate, Carbon $endDate): Collection
    {
        return Parking::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest('check_in')
            ->take(10)
            ->get();
    }

    private function buildPaymentHistory(Carbon $startDate, Carbon $endDate): Collection
    {
        $payments = Payment::query()
            ->with('parking')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest('created_at')
            ->take(10)
            ->get();

        return $this->attachStaffToPayments($payments);
    }

    private function attachStaffToPayments(Collection $payments): Collection
    {
        if ($payments->isEmpty()) {
            return collect();
        }

        $plateNumbers = $payments
            ->map(fn (Payment $payment) => $payment->parking?->vehicle_number ?? $payment->plate_number)
            ->filter()
            ->unique()
            ->values();

        $reservations = Reservation::query()
            ->with('user')
            ->where('source', 'parking')
            ->whereIn('plate_number', $plateNumbers)
            ->latest('reserved_at')
            ->get()
            ->groupBy('plate_number');

        return $payments->map(function (Payment $payment) use ($reservations) {
            $plateNumber = $payment->parking?->vehicle_number ?? $payment->plate_number;
            $staffReservation = $reservations->get($plateNumber)?->first();

            return [
                'staff_name' => $staffReservation?->user?->name ?? 'Petugas tidak diketahui',
                'plate_number' => $plateNumber ?? '-',
                'payment_method' => $payment->payment_method ?? 'cash',
                'total_bayar' => (float) ($payment->total_bayar ?? 0),
                'paid_at' => $payment->created_at,
            ];
        });
    }
}
