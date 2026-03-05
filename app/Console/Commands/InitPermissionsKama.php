<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use Illuminate\Support\Str;

class InitPermissionsKama extends Command
{
    protected $signature = 'kama:init-permissions';
    protected $description = 'Initialise les permissions prédéfinies pour Kama (exécutable plusieurs fois sans doublons)';

    public function handle()
    {
        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | DEMANDES
            |--------------------------------------------------------------------------
            */
            'cree_demande',

            'valider_demande_niveau1',
            'valider_demande_niveau2',
            'valider_demande_niveau3',

            'refuser_demande_niveau1',
            'refuser_demande_niveau2',
            'refuser_demande_niveau3',

            'voir_demande_cree',
            'voir_demande_valide_niveau1',
            'voir_demande_valide_niveau2',
            'voir_demande_valide_niveau3',

            /*
            |--------------------------------------------------------------------------
            | PAIEMENTS
            |--------------------------------------------------------------------------
            */
            'initier_paiement',
            'voir_paiement_initié',

            'valider_paiement_niveau1',
            'valider_paiement_niveau2',

            'voir_paiement_valide_niveau1',
            'voir_paiement_valide_niveau2',

            /*
            |--------------------------------------------------------------------------
            | SYSTEME
            |--------------------------------------------------------------------------
            */
            'voir_dashboard',
            'voir_historique_action',
            'extraire_rapport',

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */
            'voir_onglet_admin',
        ];

        $created = 0;
        $skipped = 0;

        foreach ($permissions as $nom) {

            if (Permission::where('nom', $nom)->exists()) {
                $skipped++;
                continue;
            }

            Permission::create([
                'id'          => (string) Str::orderedUuid(),
                'nom'         => $nom,
                'description' => $this->getPermDescription($nom),
            ]);

            $created++;
        }

        $this->newLine();
        $this->info('Initialisation des permissions Kama terminée :');
        $this->info("→ Créées : {$created}");
        $this->info("→ Déjà existantes (ignorées) : {$skipped}");
        $this->info("Total permissions dans la base : " . Permission::count());
        $this->newLine();
    }

    public function getPermDescription(string $nom): string
    {
        $descriptions = [

            /*
            |--------------------------------------------------------------------------
            | DEMANDES
            |--------------------------------------------------------------------------
            */
            'cree_demande' => 'Créer une nouvelle demande de paiement (fournisseur, avance, remboursement, etc.)',

            'valider_demande_niveau1' => 'Valider une demande au niveau 1 (contrôleur / chef de service)',
            'valider_demande_niveau2' => 'Valider une demande au niveau 2 (DAF / contrôle de gestion)',
            'valider_demande_niveau3' => 'Valider une demande au niveau 3 (DG / direction générale)',

            'refuser_demande_niveau1' => 'Refuser une demande au niveau 1 (contrôleur / chef de service)',
            'refuser_demande_niveau2' => 'Refuser une demande au niveau 2 (DAF / contrôle de gestion)',
            'refuser_demande_niveau3' => 'Refuser une demande au niveau 3 (DG / direction générale)',

            'voir_demande_cree' => 'Voir les demandes créées (les siennes ou celles de son entité)',
            'voir_demande_valide_niveau1' => 'Voir les demandes validées au niveau 1',
            'voir_demande_valide_niveau2' => 'Voir les demandes validées au niveau 2',
            'voir_demande_valide_niveau3' => 'Voir les demandes validées au niveau 3',

            /*
            |--------------------------------------------------------------------------
            | PAIEMENTS
            |--------------------------------------------------------------------------
            */
            'initier_paiement' => 'Initier ou lancer un paiement pour une demande approuvée',
            'voir_paiement_initié' => 'Voir les paiements qui ont été initiés',

            'valider_paiement_niveau1' => 'Valider un paiement au niveau 1 (contrôleur comptable)',
            'valider_paiement_niveau2' => 'Valider un paiement au niveau 2 (DAF / responsable trésorerie)',

            'voir_paiement_valide_niveau1' => 'Voir les paiements validés au niveau 1',
            'voir_paiement_valide_niveau2' => 'Voir les paiements validés au niveau 2',

            /*
            |--------------------------------------------------------------------------
            | SYSTEME
            |--------------------------------------------------------------------------
            */
            'voir_dashboard' => 'Accéder au tableau de bord principal de l\'application Kama',
            'voir_historique_action' => 'Consulter l’historique complet des actions effectuées dans le système',
            'extraire_rapport' => 'Générer et exporter des rapports (PDF, Excel, statistiques)',

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */
            'voir_onglet_admin' => 'Accéder et voir l’onglet Administration (gestion des utilisateurs, rôles et paramètres)',
        ];

        return $descriptions[$nom]
            ?? "Permission : " . str_replace('_', ' ', ucwords($nom, '_'));
    }
}