<?php

namespace App\Observers;

use App\Models\Entite;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EntiteObserver
{
    /**
     * Handle the Entite "created" event.
     */
    public function created(Entite $entite): void
    {
        // On protège toute la création dans une transaction
        DB::transaction(function () use ($entite) {

            // Normalisation du nom (comme tu l'avais, mais avec Str::slug pour plus de robustesse)
            $nom = Str::slug($entite->libelle_entite, '_');

            $permissions = [
                "valider_demande_niveau1_$nom",
                "valider_demande_niveau2_$nom",
                "valider_demande_niveau3_$nom",
                "voir_demande_valider1_$nom",
                "voir_demande_valider2_$nom",
                "voir_demande_valider123_$nom",
                "voir_demande_emis_$nom",
                "cree_demande_$nom",
                "refuser_demande_niveau1_$nom",
                "refuser_demande_niveau2_$nom",
                "refuser_demande_niveau3_$nom",
                "initier_paiement_$nom",
                "voir_paiement_initier_$nom",
                "voir_paiement_niveau1_$nom",
                "voir_paiement_niveau2_$nom",
                "valider_paiement_niveau1_$nom",
                "valider_paiement_niveau2_$nom",
                "refuser_paiement_niveau1_$nom",
                "refuser_paiement_niveau2_$nom",
                "voir_dashboard_$nom"
            ];

            foreach ($permissions as $perm) {
                // firstOrCreate pour éviter les doublons accidentels
                Permission::firstOrCreate(
                    [
                        'nom'       => $perm,
                        'entite_id' => $entite->id,
                    ],
                    [
                        // description minimale (tu pourras l'améliorer plus tard)
                        'description' => ucfirst(str_replace('_', ' ', $perm)),
                    ]
                );
            }
        });
    }
}