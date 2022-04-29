<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viewData)
    {
        $this->name = $viewData["nome"];
        $this->email = $viewData["email"];
        $this->type = $viewData["tipo"];
        $this->company = isset($viewData["empresa"]) ? $viewData["empresa"] : '';
        $this->message = $viewData["mensagem"];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Contato do site")
                    ->markdown('emails.contact', [
                        "name" => $this->name,
                        "email" => $this->email,
                        "type" => $this->type,
                        "company" => $this->company,
                        "message" => $this->message,
                    ]);
    }
}
