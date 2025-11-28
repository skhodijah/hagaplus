<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Link Reset Password Haga+')
                    ->view('emails.password-reset')
                    ->with([
                        'token' => $this->token,
                        'email' => $this->email,
                        'url'   => url(route('password.reset', ['token' => $this->token, 'email' => $this->email], false)),
                    ]);
    }
}
