<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\AssessmentSubmission;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Teacher/Courses/Lessons', [
            'course' => $course->load('lessons.assessment'),
        ]);
    }

    public function store(Request $request, Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'video_type' => ['required', 'in:youtube,upload'],
            'video_url' => ['nullable', 'string'],
            'video_upload' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-m4v,video/webm,video/ogg', 'max:209715200'],
            'release_date' => ['required', 'date'],
            'assessment_type' => ['nullable', 'in:none,quiz,exam,project'],
            'assessment_instructions' => ['nullable', 'string'],
            'assessment_due_date' => ['nullable', 'date'],
        ]);

        if ($data['video_type'] === 'youtube') {
            $request->validate([
                'video_url' => ['required', 'url'],
            ]);
        } else {
            $request->validate([
                'video_upload' => ['required', 'file'],
            ]);

            $data['video_url'] = Storage::disk('public')->putFile('course-videos', $request->file('video_upload'));
        }

        $lesson = $course->lessons()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'video_type' => $data['video_type'],
            'video_url' => $data['video_url'],
            'release_date' => $data['release_date'],
            'order' => $course->lessons()->max('order') + 1,
        ]);

        if (($data['assessment_type'] ?? 'none') !== 'none') {
            $lesson->assessment()->create([
                'type' => $data['assessment_type'],
                'instructions' => $data['assessment_instructions'] ?? null,
                'due_date' => $data['assessment_due_date'] ?? null,
            ]);
        }

        return Redirect::back()->with('success', 'Lesson added successfully.');
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if ($course->teacher_id !== Auth::id() || $lesson->course_id !== $course->id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'video_type' => ['required', 'in:youtube,upload'],
            'video_url' => ['nullable', 'string'],
            'video_upload' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-m4v,video/webm,video/ogg', 'max:209715200'],
            'release_date' => ['required', 'date'],
            'assessment_type' => ['nullable', 'in:none,quiz,exam,project'],
            'assessment_instructions' => ['nullable', 'string'],
            'assessment_due_date' => ['nullable', 'date'],
        ]);

        if ($data['video_type'] === 'youtube') {
            $request->validate([
                'video_url' => ['required', 'url'],
            ]);
        } elseif ($request->hasFile('video_upload')) {
            $data['video_url'] = Storage::disk('public')->putFile('course-videos', $request->file('video_upload'));
        } else {
            $data['video_url'] = $lesson->video_url;
        }

        $lesson->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'video_type' => $data['video_type'],
            'video_url' => $data['video_url'],
            'release_date' => $data['release_date'],
        ]);

        if (($data['assessment_type'] ?? 'none') === 'none') {
            $lesson->assessment()->delete();
        } else {
            $lesson->assessment()->updateOrCreate([], [
                'type' => $data['assessment_type'],
                'instructions' => $data['assessment_instructions'] ?? null,
                'due_date' => $data['assessment_due_date'] ?? null,
            ]);
        }

        return Redirect::back()->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        if ($course->teacher_id !== Auth::id() || $lesson->course_id !== $course->id) {
            abort(403);
        }

        if ($lesson->completions()->exists()) {
            return Redirect::back()->with('error', 'Lessons with student completions cannot be deleted.');
        }

        $lesson->delete();

        return Redirect::back()->with('success', 'Lesson deleted successfully.');
    }

    public function reorder(Request $request, Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'order' => ['required', 'array'],
        ]);

        foreach ($data['order'] as $index => $lessonId) {
            $course->lessons()->where('id', $lessonId)->update(['order' => $index + 1]);
        }

        return Redirect::back()->with('success', 'Lesson order updated.');
    }

    public function submissions(Course $course, Lesson $lesson)
    {
        if ($course->teacher_id !== Auth::id() || $lesson->course_id !== $course->id) {
            abort(403);
        }

        $lesson->load('assessment.submissions.student');

        $assessment = $lesson->assessment;

        if (! $assessment) {
            return Redirect::back()->with('error', 'This lesson has no assessment.');
        }

        return Inertia::render('Teacher/Courses/AssessmentSubmissions', [
            'course' => $course,
            'lesson' => $lesson,
            'assessment' => $assessment,
            'submissions' => $assessment->submissions()->with('student')->get(),
        ]);
    }

    public function updateSubmission(Request $request, Course $course, Lesson $lesson, AssessmentSubmission $submission)
    {
        if ($course->teacher_id !== Auth::id() || $lesson->course_id !== $course->id) {
            abort(403);
        }

        if (! $lesson->assessment || $submission->lesson_assessment_id !== $lesson->assessment->id) {
            abort(404);
        }

        $data = $request->validate([
            'status' => ['required', 'in:submitted,graded,needs_revision'],
            'feedback' => ['nullable', 'string'],
        ]);

        $submission->update([
            'status' => $data['status'],
            'feedback' => $data['feedback'] ?? null,
        ]);

        return Redirect::back()->with('success', 'Submission updated.');
    }
}
