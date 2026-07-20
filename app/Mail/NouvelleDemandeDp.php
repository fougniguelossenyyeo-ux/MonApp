<?php

namespace App\Mail;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouvelleDemandeDP extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public $validateur; // Utilisateur qui recevra le mail

    /**
     * Crée une instance de message.
     */
    public function __construct(Demande $demande, $validateur)
    {
        $this->demande = $demande;
        $this->validateur = $validateur;
    }

    /**
     * Construction du message.
     */
    public function build()
    {
        $subject = "DPaie-{$this->demande->denomination}-{$this->demande->reference_dp}-{$this->demande->entite->libelle_entite}";

        return $this->view('emails.nouvelle_demande_dp')
            ->subject($subject)
            ->with([
                'demande' => $this->demande,
                'validateur' => $this->validateur, // ⚡ On le passe à la vue
            ]);
    }
}
