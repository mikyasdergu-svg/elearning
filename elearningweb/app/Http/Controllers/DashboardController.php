<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isTeacher()) {
            if ($user->isTeacherApproved()) {
                return redirect()->route('teacher.dashboard');
            }

            return redirect()->route('teacher.pending');
        }

        $enrollments = $user->enrollments()->with('course.lessons')->get()->map(function ($enrollment) {
            $releasedLessons = $enrollment->course->lessons->filter(
                fn ($lesson) => $lesson->release_date->lte(now())
            );

            $completedLessons = $releasedLessons->filter(function ($lesson) use ($enrollment) {
                return $lesson->completions()->where('user_id', $enrollment->user_id)->exists();
            });

            $completedCount = $completedLessons->count();
            $lessonCount = $releasedLessons->count();

            $nextLesson = $releasedLessons
                ->sortBy('order')
                ->first(function ($lesson) use ($enrollment) {
                    return ! $lesson->completions()->where('user_id', $enrollment->user_id)->exists();
                });

            return [
                'id' => $enrollment->id,
                'course' => $enrollment->course,
                'paid' => $enrollment->paid,
                'price' => $enrollment->price,
                'completed_count' => $completedCount,
                'lesson_count' => $lessonCount,
                'progress' => $lessonCount ? round($completedCount * 100 / $lessonCount) : 0,
                'trial_ends_at' => $enrollment->trial_ends_at,
                'grace_ends_at' => $enrollment->grace_ends_at,
                'payment_status' => $enrollment->payment_status,
                'has_pending_payment' => $enrollment->isPaymentPending(),
                'has_access' => $enrollment->hasAccess(),
                'next_lesson_id' => $nextLesson?->id,
            ];
        });

        return Inertia::render('Student/Dashboard', [
            'enrollments' => $enrollments,
        ]);
    }
}
