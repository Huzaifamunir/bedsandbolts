<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class Sale extends Mailable
{
    use Queueable, SerializesModels;

     public $sale;

  /**
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct($sale)
  {
    
    $this->sale = $sale;
  }
  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {
    return $this->view('email.sale');
  }
}


