<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Redirect teachers to teacher dashboard
        if (Auth::check() && Auth::user()->isTeacher()) {
            return Redirect::route('teacher.dashboard');
        }

        // Redirect admins to admin dashboard
        if (Auth::check() && Auth::user()->isAdmin()) {
            return Redirect::route('admin.dashboard');
        }

        $search = $request->query('search');

        $courses = Course::published()
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Public/Home', [
            'courses' => $courses,
            'search' => $search,
        ]);
    }

    public function show(Course $course)
    {
        if ($course->status !== 'published') {
            abort(404);
        }

        $enrollment = null;
        $completedLessonIds = [];

        if (Auth::check()) {
            $record = Enrollment::where('course_id', $course->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($record) {
                $completedLessonIds = LessonCompletion::query()
                    ->where('user_id', Auth::id())
                    ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                    ->pluck('lesson_id')
                    ->all();

                $enrollment = [
                    'id' => $record->id,
                    'paid' => $record->paid,
                    'price' => $record->price,
                    'trial_ends_at' => $record->trial_ends_at,
                    'grace_ends_at' => $record->grace_ends_at,
                    'payment_status' => $record->payment_status,
                    'has_pending_payment' => $record->isPaymentPending(),
                    'has_access' => $record->hasAccess(),
                ];
            }
        }

        return Inertia::render('Course/Show', [
            'course' => $course->load('teacher', 'lessons.assessment'),
            'enrollment' => $enrollment,
            'completedLessonIds' => $completedLessonIds,
        ]);
    }

    public function enroll(Request $request, Course $course)
    {
        $user = $request->user();

        if ($course->status !== 'published') {
            abort(404);
        }

        if (! $user->isStudent()) {
            abort(403);
        }

        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'price' => $course->price,
                'paid' => false,
                'payment_status' => 'pending',
                'trial_ends_at' => now()->addDays(8),
                'grace_ends_at' => now()->addDays(8)->addHours(12),
            ]
        );

        return Redirect::route('courses.show', ['course' => $course->id])
            ->with('success', 'Your free trial has started. Enjoy the course!');
    }
}
