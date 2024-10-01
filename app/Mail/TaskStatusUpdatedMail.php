<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class TaskStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $action;
    public function __construct(Task $task, $action)
    {
        $this->task = $task;
        $this->action = $action;
    }

    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Task Status Updated Mail',
        );
    }

    
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificationTask',
        );
    }

    public function build()
    {
        return $this->subject('Task ' . $this->action)
                    ->view('emails.notificationTask');
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
