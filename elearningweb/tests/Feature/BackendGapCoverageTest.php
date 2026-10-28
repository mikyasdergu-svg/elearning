<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->teacher = User::factory()->create([
        'role' => 'teacher',
        'teacher_status' => 'approved',
    ]);

    $this->student = User::factory()->create([
        'role' => 'student',
    ]);
});

test('admin can approve a pending teacher', function () {
    $pendingTeacher = User::factory()->create([
        'role' => 'teacher',
        'teacher_status' => 'pending',
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.teachers.approve', ['user' => $pendingTeacher->id]))
        ->assertRedirect();

    expect($pendingTeacher->fresh()->teacher_status)->toBe('approved');
});

test('admin can reject a pending teacher with a reason', function () {
    $pendingTeacher = User::factory()->create([
        'role' => 'teacher',
        'teacher_status' => 'pending',
    ]);

    $this->actingAs($this->admin)
        ->from(route('admin.teachers.pending'))
        ->put(route('admin.teachers.reject', ['user' => $pendingTeacher->id]), [
            'reason' => 'Insufficient qualifications',
        ])
        ->assertRedirect();

    expect($pendingTeacher->fresh()->teacher_status)->toBe('rejected');
    expect($pendingTeacher->fresh()->teacher_rejection_reason)->toBe('Insufficient qualifications');
});

test('teacher cannot change course price after enrollment', function () {
    $course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'price' => 25.00,
        'status' => 'published',
    ]);

    Enrollment::factory()->create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'price' => 25.00,
        'paid' => false,
        'payment_status' => 'pending',
        'trial_ends_at' => now()->addDays(8),
        'grace_ends_at' => now()->addDays(8)->addHours(12),
    ]);

    $response = $this->actingAs($this->teacher)->put(route('teacher.courses.update', ['course' => $course->id]), [
        'title' => $course->title,
        'description' => $course->description,
        'price' => 35.00,
        'status' => $course->status,
    ]);

    $response->assertRedirect();
    expect((float) $course->fresh()->price)->toBe(25.0);
});

test('teacher cannot delete a course with enrolled students', function () {
    $course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'status' => 'published',
    ]);

    Enrollment::factory()->create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'price' => $course->price,
        'paid' => true,
        'payment_status' => 'succeeded',
        'trial_ends_at' => now()->subDays(1),
        'grace_ends_at' => now()->addDays(1),
    ]);

    $response = $this->actingAs($this->teacher)->delete(route('teacher.courses.destroy', ['course' => $course->id]));

    $response->assertRedirect();
    expect(Course::find($course->id))->not->toBeNull();
});

test('admin can approve a pending payment and grant enrollment access', function () {
    $course = Course::factory()->create([
        'status' => 'published',
    ]);

    $enrollment = Enrollment::factory()->create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'price' => $course->price,
        'paid' => false,
        'payment_status' => 'pending',
        'trial_ends_at' => now()->subDays(10),
        'grace_ends_at' => now()->subDays(2),
    ]);

    $payment = $enrollment->payments()->create([
        'provider' => 'stripe',
        'provider_payment_id' => (string) Str::uuid(),
        'amount' => $enrollment->price,
        'status' => 'pending',
        'details' => 'Test pending payment',
    ]);

    $response = $this->actingAs($this->admin)
        ->from(route('admin.dashboard'))
        ->put(route('admin.payments.approve', ['payment' => $payment->id]));

    $response->assertRedirect();
    expect($payment->fresh()->status)->toBe('succeeded');
    expect($enrollment->fresh()->paid)->toBe(true);
    expect($enrollment->fresh()->payment_status)->toBe('succeeded');
});

test('admin can reject a pending payment and keep enrollment unpaid', function () {
    $course = Course::factory()->create([
        'status' => 'published',
    ]);

    $enrollment = Enrollment::factory()->create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'price' => $course->price,
        'paid' => false,
        'payment_status' => 'pending',
        'trial_ends_at' => now()->subDays(10),
        'grace_ends_at' => now()->subDays(2),
    ]);

    $payment = $enrollment->payments()->create([
        'provider' => 'stripe',
        'provider_payment_id' => (string) Str::uuid(),
        'amount' => $enrollment->price,
        'status' => 'pending',
        'details' => 'Test pending payment',
    ]);

    $response = $this->actingAs($this->admin)
        ->from(route('admin.dashboard'))
        ->put(route('admin.payments.reject', ['payment' => $payment->id]), [
            'reason' => 'Invalid details',
        ]);

    $response->assertRedirect();
    expect($payment->fresh()->status)->toBe('failed');
    expect($enrollment->fresh()->paid)->toBe(false);
    expect($enrollment->fresh()->payment_status)->toBe('failed');
});

test('teacher cannot delete a lesson with completed students', function () {
    $course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'status' => 'published',
    ]);

    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    LessonCompletion::factory()->create([
        'user_id' => $this->student->id,
        'lesson_id' => $lesson->id,
    ]);

    $response = $this->actingAs($this->teacher)->delete(route('teacher.courses.lessons.destroy', ['course' => $course->id, 'lesson' => $lesson->id]));

    $response->assertRedirect();
    expect(Lesson::find($lesson->id))->not->toBeNull();
});

test('student cannot watch a lesson before its release date', function () {
    $course = Course::factory()->create([
        'status' => 'published',
    ]);

    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
        'release_date' => now()->addDays(3),
    ]);

    Enrollment::factory()->create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'price' => $course->price,
        'paid' => true,
        'payment_status' => 'succeeded',
        'trial_ends_at' => now()->subDays(1),
        'grace_ends_at' => now()->addDays(1),
    ]);

    $this->actingAs($this->student)
        ->get(route('courses.lessons.watch', ['course' => $course->id, 'lesson' => $lesson->id]))
        ->assertStatus(403);
});
