<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['enrollment.course', 'enrollment.student'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
        ]);
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            abort(404);
        }

        $payment->update([
            'status' => 'succeeded',
            'details' => 'Approved by admin',
        ]);

        $payment->enrollment->update([
            'paid' => true,
            'payment_status' => 'succeeded',
            'payment_provider' => $payment->provider,
            'payment_intent_id' => $payment->provider_payment_id,
        ]);

        return Redirect::back()->with('success', 'Payment approved successfully.');
    }

    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            abort(404);
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $payment->update([
            'status' => 'failed',
            'details' => 'Rejected by admin: '.$request->input('reason'),
        ]);

        $payment->enrollment->update([
            'payment_status' => 'failed',
        ]);

        return Redirect::back()->with('success', 'Payment rejected successfully.');
    }
}
