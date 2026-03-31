<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $finalResultText;

    public function __construct($application, $finalResult)
{
    $this->application = $application;

    if ($finalResult === 'qualified') {
        $this->finalResultText = 'MET ✅';
    } elseif ($finalResult === 'disqualified') {
        $this->finalResultText = 'NOT MET ❌';
    } else {
        $this->finalResultText = 'IN PROGRESS ⏳';
    }
}

    public function build()
    {
        return $this->subject('Application Status Result')
                    ->view('emails.application_status');
    }
}