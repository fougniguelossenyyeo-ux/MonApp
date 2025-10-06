<?php

namespace App\Mail;

use App\Models\Paiement;
use App\Models\PaiementVersement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaiementAValiderMail extends Mailable
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
        return $this->view('emails.paiement_a_valider')
            ->with([
                'paiement' => $this->paiement,
                'demande' => $this->paiement->demande,
                'versement' => $this->versement,
            ]);
    }
}
