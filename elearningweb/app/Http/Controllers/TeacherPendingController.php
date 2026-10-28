<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherPendingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Teacher/Pending', [
            'status' => $user->teacher_status,
            'rejectionReason' => $user->teacher_rejection_reason,
        ]);
    }
}
