<?php

namespace App\Mail;

use App\Models\Paiement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaiementAValiderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function build()
    {
        return $this->subject('Paiement à valider par le DG')
                    ->view('emails.paiement_a_valider');
    }
}
