<?php

namespace App\Mail;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeRefuseeDaf extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public $controleur;

    /**
     * Create a new message instance.
     */
    public function __construct(Demande $demande, $controleur = null)
    {
        $this->demande = $demande;
        $this->controleur = $controleur;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $entite = $this->demande->entite->libelle_entite ?? '-';
        $recipientName = $this->demande->user->prenom ?? 'Utilisateur';

        return $this->subject("Demande refusée par le DAF : {$this->demande->reference_dp} - {$entite}")
            ->view('emails.demande_refusee_daf')
            ->with([
                'demande' => $this->demande,
                'controleur' => $this->controleur,
                'recipientName' => $recipientName,
            ]);
    }
}
