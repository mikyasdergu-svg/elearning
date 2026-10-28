<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/Users', [
            'users' => $users,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:student,teacher,admin'],
        ]);

        $teacherStatus = $request->input('role') === 'teacher' ? 'pending' : 'approved';

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'teacher_status' => $teacherStatus,
            'teacher_rejection_reason' => null,
        ]);

        return Redirect::back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return Redirect::back()->with('error', 'The super admin account cannot be deleted.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted successfully.');
    }
}
