<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->isTeacher()) {
            abort(403);
        }

        if ($user->isTeacherApproved()) {
            return $next($request);
        }

        return Redirect::route('teacher.pending');
    }
}
