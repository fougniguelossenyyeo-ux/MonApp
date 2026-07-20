<?php

namespace App\Mail;

use App\Models\Demande;
use App\Models\User; // <-- Ajoute cette ligne
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationDAF extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public $daf;

    public function __construct(Demande $demande)
    {
        $this->demande = $demande;

        // Récupérer le DAF correctement
        $this->daf = User::whereHas('role', function ($q) use ($demande) {
            $q->where('entite_id', $demande->entite_id)
                ->whereRaw('LOWER(libelle) = ?', ['daf']);
        })->first();
    }

    public function build()
    {
        $entite = $this->demande->entite->libelle_entite ?? '-';
        $recipientName = $this->daf->prenom ?? 'DAF';

        return $this->subject("Nouvelle demande à valider : {$this->demande->reference_dp} - {$this->demande->denomination} ({$entite})")
            ->view('emails.notification_daf')
            ->with([
                'demande' => $this->demande,
                'daf' => $this->daf,
                'recipientName' => $recipientName,
            ]);
    }
}
