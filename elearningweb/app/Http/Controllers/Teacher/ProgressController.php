<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProgressController extends Controller
{
    public function show(Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $students = $course->enrollments()->with('student')->get()->map(function ($enrollment) use ($course) {
            $completedLessons = $course->lessons()->whereHas('completions', function ($query) use ($enrollment) {
                $query->where('user_id', $enrollment->user_id);
            })->get();

            $lessonCount = $course->lessons()->count();
            $completedCount = $completedLessons->count();

            return [
                'student' => $enrollment->student,
                'enrolled_at' => $enrollment->created_at,
                'paid' => $enrollment->paid,
                'completion_percentage' => $lessonCount ? round($completedCount * 100 / $lessonCount) : 0,
                'completed_lessons' => $completedLessons->map(fn ($lesson) => [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                ])->all(),
            ];
        });

        return Inertia::render('Teacher/Courses/Progress', [
            'course' => $course,
            'students' => $students,
        ]);
    }
}
