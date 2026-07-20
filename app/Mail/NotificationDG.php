<?php

namespace App\Mail;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationDG extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public $initiateur;

    public $controleur;

    /**
     * Crée une nouvelle instance du message.
     */
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;

        // Récupérer l'initiateur
        $this->initiateur = $demande->user;

        // Récupérer le contrôleur de la même entité
        $this->controleur = \App\Models\User::whereHas('role', function ($q) use ($demande) {
            $q->whereRaw('LOWER(libelle) = ?', ['controleur'])
                ->where('entite_id', $demande->entite_id);
        })->first();
    }

    /**
     * Construction du message.
     */
    public function build()
    {
        $entite = $this->demande->entite->libelle_entite ?? '-';
        $recipientName = 'DG'; // On peut l’adapter si tu veux le nom du DG spécifique

        return $this->subject("Nouvelle demande à valider : {$this->demande->reference_dp} - {$this->demande->denomination} ({$entite})")
            ->view('emails.notification_dg')
            ->with([
                'demande' => $this->demande,
                'initiateur' => $this->initiateur,
                'controleur' => $this->controleur,
                'recipientName' => $recipientName,
            ]);
    }
}
