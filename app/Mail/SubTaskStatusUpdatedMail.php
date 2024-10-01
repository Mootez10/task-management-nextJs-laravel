<?php

namespace App\Mail;

use App\Models\SubTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubTaskStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subtask;
    public $action;
    public function __construct(SubTask $subtask, $action)
    {
        $this->subtask = $subtask;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sub Task Status Updated Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificationSubtask',
        );
    }

    public function build()
    {
        return $this->subject('SubTask ' . $this->action)
                    ->view('emails.notificationSubtask');
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
