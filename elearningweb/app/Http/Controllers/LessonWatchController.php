<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LessonWatchController extends Controller
{
    public function show(Request $request, Course $course, Lesson $lesson)
    {
        if ($course->status !== 'published' || $lesson->course_id !== $course->id) {
            abort(404);
        }

        if ($lesson->release_date->isFuture()) {
            abort(403);
        }

        $enrollment = $request->user()->enrollments()->where('course_id', $course->id)->first();

        if (! $enrollment || ! $enrollment->hasAccess()) {
            abort(403);
        }

        $lesson->load('assessment');

        $isCompleted = $request->user()
            ->lessonCompletions()
            ->where('lesson_id', $lesson->id)
            ->exists();

        return Inertia::render('Student/LessonWatch', [
            'course' => $course,
            'lesson' => $lesson,
            'enrollment' => $enrollment,
            'isCompleted' => $isCompleted,
        ]);
    }

    public function complete(Request $request, Course $course, Lesson $lesson)
    {
        if ($course->status !== 'published' || $lesson->course_id !== $course->id) {
            abort(404);
        }

        if ($lesson->release_date->isFuture()) {
            abort(403);
        }

        $enrollment = $request->user()->enrollments()->where('course_id', $course->id)->first();

        if (! $enrollment || ! $enrollment->hasAccess()) {
            abort(403);
        }

        LessonCompletion::firstOrCreate([
            'user_id' => $request->user()->id,
            'lesson_id' => $lesson->id,
        ]);

        return Redirect::back()->with('success', 'Lesson marked complete.');
    }
}
