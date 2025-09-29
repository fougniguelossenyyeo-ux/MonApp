<?php

namespace App\Mail;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeRefusee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    /**
     * Crée une nouvelle instance du mail.
     */
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Construction du message.
     */
    public function build()
    {
        $entite = $this->demande->entite->libelle_entite ?? '-';
        $subject = "Demande refusée : {$this->demande->reference_dp} - {$this->demande->denomination} ({$entite})";

        return $this->subject($subject)
                    ->view('emails.demande_refusee')
                    ->with([
                        'demande' => $this->demande,
                    ]);
    }
}
