<?php

namespace App\Mail;

use App\Models\Paiement;
use App\Models\PaiementVersement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaiementRefuseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement;

    public $versement;

    public function __construct(Paiement $paiement, PaiementVersement $versement)
    {
        $this->paiement = $paiement;
        $this->versement = $versement;
    }

    public function build()
    {
        return $this->subject('Votre versement a été refusé')
            ->view('emails.versement_refuser')
            ->with([
                'paiement' => $this->paiement,
                'versement' => $this->versement,
                'demande' => $this->paiement->demande,
            ]);
    }
}
