<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;
use Carbon\Carbon;

class CheckRegistrationDeadline
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $openDate = SystemSetting::where('key', 'registration_open_date')->value('value');
        $closeDate = SystemSetting::where('key', 'registration_close_date')->value('value');
        
        $now = Carbon::now();
        $open = $openDate ? Carbon::parse($openDate)->startOfDay() : null;
        $close = $closeDate ? Carbon::parse($closeDate)->endOfDay() : null;

        if (($open && $now->isBefore($open)) || ($close && $now->isAfter($close))) {
            return redirect('/')->with('error', 'Registration is currently closed.');
        }

        return $next($request);
    }
}
