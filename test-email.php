<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MassEmailNotification;

try {
    $students = User::role('Student')->take(1)->get();
    echo "Sending to " . $students->count() . " students...\n";
    Notification::send($students, new MassEmailNotification("Test Subject", "Test message"));
    echo "SUCCESS sending email!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile() . "\n";
}
