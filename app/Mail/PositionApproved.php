<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PositionApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($position)
    {
        $this->position = $position;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->position->user->email)
                    ->subject("Sua vaga foi aprovada.")
                    ->markdown('emails.position_approved', [
                        "position" => $this->position,
                        "url" => "https://www.inklua.com.br/token/113758/lid=Cj0KCQjwpdqDBhCSARIsAEUJ0hMMZEsJJrb2hzW8uOVGKxWq62PZeeoWwIaewgbOqi_uwLL8jHwQ72AaApIUEALw_wcB"
                    ]);
    }
}
