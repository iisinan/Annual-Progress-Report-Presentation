<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = \App\Models\Student::has('presentation')->get()->filter(function($s) {
    $progName = strtolower($s->programme->name);
    $isPhd = str_contains($progName, 'phd') || str_contains($progName, 'doctor');
    $requiredCount = $isPhd ? 3 : 2;
    return $s->supervisors()->count() < $requiredCount;
})->first();

if ($student) {
    echo "Found student ID: {$student->id}, User ID: {$student->user_id}\n";
    $request = Illuminate\Http\Request::create('/dashboard', 'GET');
    $request->setUserResolver(fn() => $student->user);
    $response = app()->handle($request);
    echo "Response status for /dashboard: " . $response->getStatusCode() . "\n";
    echo "Redirect Location: " . $response->headers->get('Location') . "\n";
} else {
    echo "No student with PPT and insufficient supervisors.\n";
}
