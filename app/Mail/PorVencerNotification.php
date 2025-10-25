<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PorVencerNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $nicho;
    public $osario;
    public $difunto;
    public $logoUrl;

    public function __construct($nicho = null, $osario = null, $difunto = null)
    {
        $this->nicho = $nicho;
        $this->osario = $osario;
        $this->difunto = $difunto;
        $this->logoUrl = 'https://i.imgur.com/TpdeOo1_d.png';
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Notificación: Alquiler por vencer')
                    ->view('emails.por_vencer')
                    ->with([
                        'logoUrl' => $this->logoUrl
                    ]);
    }
}
