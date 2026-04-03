<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $name
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Email - Web Architect',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-verification',
        );
    }
}
