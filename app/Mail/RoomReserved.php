<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RoomReserved extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $folio;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $folio)
    {
        $this->data = $data;
        $this->folio = $folio;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Solicitud - La Casona',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.room_reserved', // Esta es la vista HTML que vamos a crear
        );
    }
}