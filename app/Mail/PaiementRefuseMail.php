<?php

namespace App\Mail;

use App\Models\Paiement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaiementRefuseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function build()
    {
        return $this->subject('Votre paiement a été refusé')
                    ->view('emails.paiements.refuse')
                    ->with([
                        'paiement' => $this->paiement,
                        'demande' => $this->paiement->demande,
                    ]);
    }
}
