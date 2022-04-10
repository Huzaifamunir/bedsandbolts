<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class sendmail extends Mailable
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
    return $this->from(env('MAIL_FROM'))->subject('YOUR FORM IS SUBMITTED SUCCESSFULLY')->view('email.sendmail')->with('sendmail', $this->sendmail);
  }
}


