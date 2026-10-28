<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start') ? Carbon::parse($request->query('start'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->query('end') ? Carbon::parse($request->query('end'))->endOfDay() : Carbon::now()->endOfDay();

        $sales = Enrollment::with('course', 'student')
            ->where('paid', true)
            ->whereBetween('updated_at', [$start, $end])
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalRevenue = $sales->sum('price');
        $revenueByCourse = $sales->groupBy('course_id')->map(function ($group) {
            return [
                'course' => $group->first()->course,
                'revenue' => $group->sum('price'),
                'enrollments' => $group->count(),
            ];
        })->values();

        return Inertia::render('Admin/Sales', [
            'sales' => $sales,
            'totalRevenue' => $totalRevenue,
            'revenueByCourse' => $revenueByCourse,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
        ]);
    }
}
