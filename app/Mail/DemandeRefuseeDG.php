<?php

namespace App\Mail;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeRefuseeDG extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $daf;
    public $controleur;

    /**
     * Create a new message instance.
     */
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;

        // Récupérer le DAF de l'entité
        $this->daf = User::whereHas('role', function($q) use ($demande) {
            $q->where('entite_id', $demande->entite_id)
              ->whereRaw('LOWER(libelle) = ?', ['daf']);
        })->first();

        // Récupérer le contrôleur de l'entité
        $this->controleur = User::whereHas('role', function($q) use ($demande) {
            $q->where('entite_id', $demande->entite_id)
              ->whereRaw('LOWER(libelle) = ?', ['controleur']);
        })->first();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $entite = $this->demande->entite->libelle_entite ?? '-';
        $recipientName = $this->demande->user->prenom ?? 'Utilisateur';

        return $this->subject("Demande refusée : {$this->demande->reference_dp} - {$this->demande->denomination} ({$entite})")
                    ->view('emails.demande_refusee_dg')
                    ->with([
                        'demande' => $this->demande,
                        'daf' => $this->daf,
                        'controleur' => $this->controleur,
                        'recipientName' => $recipientName,
                    ]);
    }
}
