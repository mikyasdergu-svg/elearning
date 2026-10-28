<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function show(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();

        if ($enrollment->user_id !== $user->id) {
            abort(403);
        }

        if ($enrollment->paid) {
            return Redirect::route('courses.show', ['course' => $enrollment->course_id]);
        }

        if ($enrollment->payments()->where('status', 'pending')->exists()) {
            return Redirect::route('courses.show', ['course' => $enrollment->course_id])
                ->with('info', 'Your payment is pending admin verification.');
        }

        return Inertia::render('Checkout', [
            'enrollment' => $enrollment->load('course'),
            'stripePublicKey' => env('STRIPE_PUBLIC_KEY'),
            'stripeMode' => env('STRIPE_TEST_MODE', true),
        ]);
    }

    public function pay(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();

        if ($enrollment->user_id !== $user->id) {
            abort(403);
        }

        if ($enrollment->paid) {
            return Redirect::route('courses.show', ['course' => $enrollment->course_id]);
        }

        if ($enrollment->payment_status === 'pending' && $enrollment->payments()->where('status', 'pending')->exists()) {
            return Redirect::route('courses.show', ['course' => $enrollment->course_id])
                ->with('info', 'Your payment is already pending admin verification.');
        }

        $payment = $enrollment->payments()->create([
            'provider' => 'stripe',
            'provider_payment_id' => Str::uuid(),
            'amount' => $enrollment->price,
            'status' => 'pending',
            'details' => 'Payment submitted and awaiting admin verification',
        ]);

        $enrollment->update([
            'paid' => false,
            'payment_status' => 'pending',
            'payment_provider' => 'stripe',
            'payment_intent_id' => $payment->provider_payment_id,
        ]);

        return Redirect::route('courses.show', ['course' => $enrollment->course_id])->with('success', 'Payment submitted. Awaiting admin verification.');
    }
}
