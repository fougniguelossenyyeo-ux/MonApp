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
     public $controleurs;
    /**
     * Cree une instance de message.
     */
   public function __construct(Demande $demande, $controleurs)
{
    $this->demande = $demande;
    $this->controleurs = $controleurs;
}


    /**
     * construction du message.
     */
    public function build()
{
    $subject = "DPaie-{$this->demande->denomination}-{$this->demande->reference_dp}-{$this->demande->entite->nom}";

    return $this->view('emails.nouvelle_demande_dp')
                ->subject($subject)
                ->with([
                    'demande' => $this->demande,
                    'controleurs' => $this->controleurs
                ]);
}
}
