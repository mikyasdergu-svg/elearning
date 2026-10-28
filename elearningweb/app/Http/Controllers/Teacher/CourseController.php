<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = $request->user()->courses()->withCount('enrollments')->get();

        return Inertia::render('Teacher/Courses/Index', [
            'courses' => $courses,
        ]);
    }

    public function create()
    {
        return Inertia::render('Teacher/Courses/Form', [
            'course' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:50'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', 'in:draft,published'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['teacher_id'] = $request->user()->id;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        Course::create($data);

        return Redirect::route('teacher.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Teacher/Courses/Form', [
            'course' => $course,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:50'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', 'in:draft,published'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($course->enrollments()->exists() && $data['price'] != $course->price) {
            return Redirect::back()->with('error', 'Cannot change price after students have enrolled.');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course->update($data);

        return Redirect::route('teacher.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        if ($course->enrollments()->exists()) {
            return Redirect::back()->with('error', 'A course with enrolled students cannot be deleted.');
        }

        $course->delete();

        return Redirect::route('teacher.courses.index')->with('success', 'Course deleted successfully.');
    }
}
