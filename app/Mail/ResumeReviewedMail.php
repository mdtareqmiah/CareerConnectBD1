<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResumeReviewedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public array $data, public string $subject)
    {
    }

    public function build(): self
    {
        return $this->subject($this->subject)
            ->markdown('emails.resume-reviewed');
    }
}
