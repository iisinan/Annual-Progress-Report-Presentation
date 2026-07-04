<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;
use Carbon\Carbon;

class CheckUploadDeadline
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $openDate = SystemSetting::where('key', 'upload_open_date')->value('value');
        $closeDate = SystemSetting::where('key', 'upload_close_date')->value('value');
        $isUploadActive = SystemSetting::where('key', 'is_upload_active')->value('value') ?? 'auto';

        if ($isUploadActive == '1') {
            return $next($request); // Force Open
        }

        if ($isUploadActive == '0') {
            return redirect()->route('dashboard')->with('error', 'Presentation upload has been temporarily disabled by the administrator.');
        }

        // Otherwise 'auto' -> Check dates
        $now = Carbon::now();
        $open = $openDate ? Carbon::parse($openDate)->startOfDay() : null;
        $close = $closeDate ? Carbon::parse($closeDate)->endOfDay() : null;

        if (($open && $now->isBefore($open)) || ($close && $now->isAfter($close))) {
            return redirect()->route('dashboard')->with('error', 'Presentation upload is currently closed.');
        }

        return $next($request);
    }
}
