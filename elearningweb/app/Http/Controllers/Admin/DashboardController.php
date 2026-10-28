<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalRevenue = Payment::where('status', 'succeeded')->sum('amount');
        $totalUsers = User::count();
        $totalCourses = Course::count();
        $teacherCount = User::where('role', 'teacher')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        return Inertia::render('Admin/Dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'teacherCount' => $teacherCount,
            'pendingTeachers' => User::where('role', 'teacher')->where('teacher_status', 'pending')->count(),
            'pendingPayments' => $pendingPayments,
        ]);
    }
}
