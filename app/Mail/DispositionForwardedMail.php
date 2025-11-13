<?php

namespace App\Mail;

use App\Models\Disposition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DispositionForwardedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $disposition;

    /**
     * Create a new message instance.
     */
    public function __construct(Disposition $disposition)
    {
        $this->disposition = $disposition;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = '[DISPOSISI TERUSAN] ' . $this->disposition->subject;
        
        return $this->subject($subject)
                    ->markdown('emails.disposition-forwarded')
                    ->with([
                        'disposition' => $this->disposition,
                        'originalSender' => $this->disposition->forwardedFrom->fromUser ?? null,
                    ]);
    }
}