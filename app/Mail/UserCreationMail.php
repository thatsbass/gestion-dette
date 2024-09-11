<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class UserCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $pdfPath;

   

    public function __construct($user, $pdfPath)
    {
        $this->user = $user;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Carte de fidelite',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user_creation', 
        );
    }

    public function build()
    {
        return $this->attach($this->pdfPath, [
                'as' => 'Carte.pdf',
                'mime' => 'application/pdf',
            ])
            ->with([
                'user' => $this->user,
            ]);
    }
    
}
