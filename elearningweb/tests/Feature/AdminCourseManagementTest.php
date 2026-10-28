<?php

use App\Models\Course;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => 'admin',
    ]);
});

test('admin can view course management page', function () {
    Course::factory()->create([ 'title' => 'Admin Course' ]);

    $this->actingAs($this->admin)
        ->get(route('admin.courses.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Courses/Index')
            ->where('courses', fn ($courses) => count($courses) === 1)
        );
});

test('admin can delete a course', function () {
    $course = Course::factory()->create([ 'title' => 'Delete Me' ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.courses.destroy', [ 'course' => $course->id ]));

    $response->assertRedirect();
    expect(Course::find($course->id))->toBeNull();
});
