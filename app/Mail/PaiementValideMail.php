<?php

namespace App\Mail;

use App\Models\Paiement;
use App\Models\PaiementVersement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaiementValideMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement;
    public $versement;

    /**
     * Create a new message instance.
     *
     * @param Paiement $paiement
     * @param PaiementVersement $versement
     */
    public function __construct(Paiement $paiement, PaiementVersement $versement)
    {
        $this->paiement = $paiement;
        $this->versement = $versement;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Paiement validé')
                    ->view('emails.valide')
                    ->with([
                        'paiement' => $this->paiement,
                        'demande' => $this->paiement->demande,
                        'versement' => $this->versement,
                    ]);
    }
}
