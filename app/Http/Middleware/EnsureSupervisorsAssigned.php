<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSupervisorsAssigned
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Prevent infinite redirect loop
        if ($request->routeIs('student.supervisors.create') || $request->routeIs('student.supervisors.store') || $request->routeIs('logout')) {
            return $next($request);
        }

        $user = $request->user();

        if ($user && $user->hasRole('Student')) {
            $student = $user->student;
            
            if ($student && $student->programme) {
                if ($student->supervisors()->count() < 1) {
                    return redirect()->route('student.supervisors.create')->with('warning', 'Please assign your supervisor(s) before continuing.');
                }
            }
        }

        return $next($request);
    }
}
