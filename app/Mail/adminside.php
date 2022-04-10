<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class adminside extends Mailable
{
    use Queueable, SerializesModels;

     public $sendmail;

  /**
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct($sendmail)
  {

    $this->sendmail = $sendmail;
  }
  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {
    return $this->from(env('MAIL_FROM'))->subject('new user conform your order')->view('email.usermail')->with('sendmail', $this->sendmail);
  }
}


