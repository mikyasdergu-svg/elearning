<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with('teacher')
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
        ]);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return Redirect::back()->with('success', 'Course deleted successfully.');
    }
}
