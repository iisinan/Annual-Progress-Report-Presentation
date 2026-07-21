<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : The email address to send the test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the SMTP configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Attempting to send a test email to: {$email}");

        try {
            Mail::raw('This is a test email to verify that your SMTP settings in Laravel Cloud are working correctly! If you receive this, your mailing configuration is perfect.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('ACETEL SMTP Test Successful');
            });
            $this->info('✅ Success! The test email was sent without any errors.');
            $this->info('Please check your inbox (and spam folder) for the email.');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email. There is an error with your SMTP configuration.');
            $this->error('Error details: ' . $e->getMessage());
        }
    }
}
