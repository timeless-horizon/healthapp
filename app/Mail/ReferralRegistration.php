<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $referrerName,
        public string $newUserName,
        public int $totalRegistrations
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New User Registration via Your Referral Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.referral-registration',
            with: [
                'referrerName' => $this->referrerName,
                'newUserName' => $this->newUserName,
                'totalRegistrations' => $this->totalRegistrations,
            ],
        );
    }
} 