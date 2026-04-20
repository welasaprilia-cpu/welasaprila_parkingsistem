<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payment::orderBy('created_at', 'desc')->paginate(10);
        $totalPaid = Payment::where('status', 'paid')->sum('total_amount') ?? 0;
        $totalPending = Payment::where('status', 'pending')->sum('total_amount') ?? 0;
        $totalTransactions = Payment::count();

        return view('payments.index', compact('payments', 'totalPaid', 'totalPending', 'totalTransactions'));
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }
}

