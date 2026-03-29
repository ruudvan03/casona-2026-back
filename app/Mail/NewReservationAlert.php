<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReservationAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Nueva Solicitud de Reserva - La Casona')
                    ->view('emails.new_reservation');
    }
}