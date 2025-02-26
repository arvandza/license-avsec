<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $url;
    /**
     * Create a new message instance.
     */
    public function __construct($title, $message, $url)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Penting - Lisensi',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.mail',
            with: [
                'title' => $this->title,
                'message' => $this->message,
                'url' => $this->url
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
