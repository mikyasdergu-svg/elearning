<?php

namespace App\Http\Controllers;

use App\Models\AssessmentSubmission;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AssessmentController extends Controller
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

        $assessment = $lesson->assessment;

        if (! $assessment) {
            abort(404);
        }

        $submission = AssessmentSubmission::firstWhere([
            'lesson_assessment_id' => $assessment->id,
            'user_id' => $request->user()->id,
        ]);

        return Inertia::render('Student/Assessment', [
            'course' => $course,
            'lesson' => $lesson,
            'assessment' => $assessment,
            'submission' => $submission,
        ]);
    }

    public function submit(Request $request, Course $course, Lesson $lesson)
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

        $assessment = $lesson->assessment;

        if (! $assessment) {
            abort(404);
        }

        $data = $request->validate([
            'response' => ['nullable', 'string'],
            'submission_file' => ['nullable', 'file', 'mimetypes:application/pdf,image/jpeg,image/png,application/zip,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:3145728'],
        ]);

        $submission = AssessmentSubmission::firstOrNew([
            'lesson_assessment_id' => $assessment->id,
            'user_id' => $request->user()->id,
        ]);

        if ($request->hasFile('submission_file')) {
            $submission->file_path = Storage::disk('public')->putFile('assessment-submissions', $request->file('submission_file'));
            $submission->submission_type = 'file';
        } elseif ($submission->file_path) {
            $submission->submission_type = 'file';
        } else {
            $submission->submission_type = 'text';
        }

        $submission->response = $data['response'] ?? null;
        $submission->status = 'submitted';
        $submission->save();

        return Redirect::back()->with('success', 'Your assessment submission is saved.');
    }
}
