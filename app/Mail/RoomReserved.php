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

    /**
     * Variable pública para que esté disponible en la vista Blade.
     */
    public $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation)
    {
        // Recibimos el objeto completo desde el controlador
        $this->reservation = $reservation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Reserva ' . $this->reservation->folio . ' - La Casona',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.room_reserved', 
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}