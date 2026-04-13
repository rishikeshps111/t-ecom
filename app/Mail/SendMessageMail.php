<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subjectText;
    public $messageText;
    public $attachments;

    public function __construct($user, $subjectText, $messageText, $attachments = [])
    {
        $this->user = $user;
        $this->subjectText = $subjectText;
        $this->messageText = $messageText;
        $this->attachments = $attachments;
    }

    public function build()
    {
        $mail = $this->subject($this->subjectText)
            ->view('emails.message')
            ->with([
                'user' => $this->user,
                'messageText' => $this->messageText,
            ]);

        // Attach files
        foreach ($this->attachments as $file) {
            $mail->attach(public_path($file));
        }

        return $mail;
    }
}
