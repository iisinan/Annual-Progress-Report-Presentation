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
        $user = $request->user();

        if ($user && $user->hasRole('Student')) {
            $student = $user->student;
            
            if ($student && $student->programme) {
                $progName = strtolower($student->programme->name);
                $isPhd = str_contains($progName, 'phd') || str_contains($progName, 'doctor');
                $requiredCount = $isPhd ? 3 : 2;

                if ($student->supervisors()->count() < $requiredCount) {
                    return redirect()->route('student.supervisors.create')->with('warning', 'Please assign your supervisors before continuing.');
                }
            }
        }

        return $next($request);
    }
}
