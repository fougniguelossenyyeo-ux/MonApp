<?php

namespace App\Mail;

use App\Models\Demande;
use App\Models\User; // Pour récupérer Trésorie, DAF et Contrôleur
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationTresorie extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public $tresorie;      // utilisateur Trésorie

    public $controleur;    // utilisateur Contrôleur

    public $daf;           // utilisateur DAF

    /**
     * Create a new message instance.
     */
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;

        // Récupérer la Trésorie de l'entité
        $this->tresorie = User::whereHas('role', function ($q) use ($demande) {
            $q->where('entite_id', $demande->entite_id)
                ->whereRaw('LOWER(libelle) = ?', ['tresorie']);
        })->first();

        // Récupérer le Contrôleur
        $this->controleur = User::whereHas('role', function ($q) use ($demande) {
            $q->where('entite_id', $demande->entite_id)
                ->whereRaw('LOWER(libelle) = ?', ['controleur']);
        })->first();

        // Récupérer le DAF qui a validé
        $this->daf = User::whereHas('role', function ($q) use ($demande) {
            $q->where('entite_id', $demande->entite_id)
                ->whereRaw('LOWER(libelle) = ?', ['daf']);
        })->first();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $entite = $this->demande->entite->libelle_entite ?? '-';
        $recipientName = $this->tresorie->prenom ?? 'Trésorie';

        return $this->subject("DPaie - Nouvelle demande validée par le DG : {$this->demande->reference_dp} ({$entite})")
            ->view('emails.notification_tresorie')
            ->with([
                'demande' => $this->demande,
                'tresorie' => $this->tresorie,
                'controleur' => $this->controleur,
                'daf' => $this->daf,
                'recipientName' => $recipientName,
            ]);
    }
}
