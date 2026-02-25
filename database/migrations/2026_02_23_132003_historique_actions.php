<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table historique_actions destinée à enregistrer TOUTES les actions importantes
     * effectuées dans l'application (création, soumission, validation, rejet, paiement, etc.).
     *
     * Objectifs principaux :
     * - Traçabilité complète pour audit comptable / contrôle interne
     * - Possibilité de reconstruire l'historique d'une demande ou d'un paiement
     * - Recherche performante même avec plusieurs centaines de milliers de lignes
     * - Compatibilité MySQL InnoDB (indexation soignée)
     */
    public function up(): void
    {
        Schema::create('historique_actions', function (Blueprint $table) {

            // Identifiant unique de chaque ligne d'historique
            $table->uuid('id')->primary();

            // -----------------------------------------------------------------
            // Qui a effectué l'action ?
            // -----------------------------------------------------------------
            $table->foreignUuid('user_id')
                  ->nullable()                      // nullable → on garde la trace même si l'utilisateur est supprimé
                  ->constrained('users')
                  ->onDelete('set null')            // le plus sûr pour l'audit
                  ->comment('Utilisateur qui a réalisé l\'action (NULL si compte supprimé)');

            // -----------------------------------------------------------------
            // Quelle action a été faite ?
            // -----------------------------------------------------------------
            $table->string('action', 80)
                  ->comment('Type d\'action : creer_demande, soumettre_demande, valider_controleur, rejeter_daf, enregistrer_versement, annuler_paiement, etc. (snake_case recommandé)');

            // -----------------------------------------------------------------
            // Sur quelle entité l'action a été faite ? (polymorphisme Laravel)
            // -----------------------------------------------------------------
            $table->uuidMorphs('subject')
                  ->comment('Entité concernée : ex. App\\Models\\Demande + uuid de la demande');

            // -----------------------------------------------------------------
            // Détails / ancien → nouveau / commentaire / contexte
            // -----------------------------------------------------------------
            $table->json('properties')
                  ->nullable()
                  ->comment('Données JSON : old_values, new_values, commentaire, montant modifié, motif rejet, etc.');

            // -----------------------------------------------------------------
            // Métadonnées de contexte / sécurité
            // -----------------------------------------------------------------
            $table->string('ip_address', 45)
                  ->nullable()
                  ->comment('Adresse IP de l\'utilisateur au moment de l\'action (IPv4 ou IPv6)');

            $table->string('user_agent', 255)
                  ->nullable()
                  ->comment('User-Agent du navigateur / application utilisée');

            // Contexte organisationnel (très utile dans un groupe multi-entités)
            $table->foreignUuid('entite_id')
                  ->nullable()
                  ->constrained('entites')
                  ->nullOnDelete()
                  ->comment('Entité / filiale / direction concernée par l\'action');

            // -----------------------------------------------------------------
            // Horodatage
            // -----------------------------------------------------------------
            $table->timestamp('created_at')
                  ->useCurrent()
                  ->comment('Date et heure exacte de l\'action');

            // Pas de updated_at : un historique ne devrait jamais être modifié

            // -----------------------------------------------------------------
            // INDEXATION – CRITIQUE pour les performances sur cette table
            // -----------------------------------------------------------------
            $table->index('user_id', 'idx_historique_user_id');
            $table->index('action', 'idx_historique_action');
            $table->index(['subject_type', 'subject_id'], 'idx_historique_subject');
            $table->index('entite_id', 'idx_historique_entite_id');
            $table->index('created_at', 'idx_historique_created_at');

            // Index composite fréquent : une demande + chronologique
            $table->index(['subject_type', 'subject_id', 'created_at'], 'idx_historique_subject_time');

            // Optionnel : si vous cherchez souvent par IP (fraude, investigation)
            $table->index('ip_address', 'idx_historique_ip');
        });
    }

    /**
     * Supprime la table en cas de rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_actions');
    }
};