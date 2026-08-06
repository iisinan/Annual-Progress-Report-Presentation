<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $supervisors = \App\Models\User::role('Supervisor')->get();
    foreach ($supervisors as $user) {
        \Auth::login($user);
        $students = \Auth::user()->supervisees()->with('presentation')->get();
        
        $stats = [
            'total' => $students->count(),
            'pending' => $students->filter(fn($s) => $s->pivot->status === 'pending')->count(),
            'approved' => $students->filter(fn($s) => $s->pivot->status === 'approved')->count(),
            'scheduled' => $students->filter(fn($s) => $s->schedule !== null)->count(),
        ];
        
        $html = view('supervisor.dashboard', compact('students', 'stats'))->render();
        echo "SUCCESS rendering view for user " . $user->id . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile() . "\n";
}
