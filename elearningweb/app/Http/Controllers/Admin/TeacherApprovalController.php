<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TeacherApprovalController extends Controller
{
    public function index()
    {
        $pendingTeachers = User::where('role', 'teacher')
            ->where('teacher_status', 'pending')
            ->get();

        return Inertia::render('Admin/Teachers/Pending', [
            'pendingTeachers' => $pendingTeachers,
        ]);
    }

    public function approve(User $user)
    {
        if ($user->role !== 'teacher') {
            abort(404);
        }

        $user->update([
            'teacher_status' => 'approved',
            'teacher_rejection_reason' => null,
        ]);

        return Redirect::back()->with('success', 'Teacher approved successfully.');
    }

    public function reject(Request $request, User $user)
    {
        if ($user->role !== 'teacher') {
            abort(404);
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->update([
            'teacher_status' => 'rejected',
            'teacher_rejection_reason' => $request->input('reason'),
        ]);

        return Redirect::back()->with('success', 'Teacher rejected successfully.');
    }
}
