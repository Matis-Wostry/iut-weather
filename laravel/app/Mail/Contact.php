<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * This constructor is used to initialize the email object.
     * If additional data needs to be passed to the email template,
     * it should be added as constructor parameters.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the email envelope.
     *
     * The envelope contains metadata about the email, such as the subject.
     *
     * @return Envelope The email envelope with a defined subject.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Weather forecast',
        );
    }

    /**
     * Get the email content definition.
     *
     * This method defines which view will be used as the email body.
     *
     * @return Content The content configuration for the email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the email.
     *
     * If the email needs to include attachments, they should be added here.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment> An array of attachments (empty if none).
     */
    public function attachments(): array
    {
        return [];
    }
}
