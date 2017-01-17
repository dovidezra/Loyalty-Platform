<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CouponRedeemed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The subject.
     *
     * @var string
     */
    public $subject;

    /**
     * The body.
     *
     * @var string
     */
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        view()->addNamespace('template', public_path() . '/templates');

        return $this->subject($this->subject)
                    ->view('template::admin.mail-coupon-redeemed');
    }
}
