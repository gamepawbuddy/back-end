<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ResetPasswordMail
 *
 * This class is responsible for sending a reset password email to the user.
 * It uses the Laravel Mailable class to define and send the email.
 *
 * @package App\Mail
 */
class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The reset token used to verify the user's request to reset their password.
     *
     * @var string
     */
    public $token;

    /**
     * ResetPasswordMail constructor.
     *
     * @param string $token The reset token.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the reset password email message.
     *
     * This method defines the view to be used for the email and passes the reset token to it.
     *
     * @return $this
     */
    public function build()
    {
        return $this-->subject('Reset password')->view('emails.user.resetPasswordMail')->with('token', $this->token);
    }
}