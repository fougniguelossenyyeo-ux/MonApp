<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_actions', function (Blueprint $table) {

            // Clé primaire UUID
            $table->uuid('id')->primary();

            // Utilisateur ayant effectué l'action
            $table->foreignUuid('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Utilisateur ayant réalisé l\'action (NULL si supprimé)');

            // Type d'action
            $table->string('action', 80)
                  ->comment('Type d\'action (snake_case recommandé)');

            // Polymorphisme (lié à n'importe quel modèle)
            $table->uuidMorphs('subject');

            // Données supplémentaires
            $table->json('properties')
                  ->nullable()
                  ->comment('Anciennes valeurs, nouvelles valeurs, commentaire, motif rejet, etc.');

            $table->string('ip_address', 45)
                  ->nullable()
                  ->comment('Adresse IP IPv4 ou IPv6');

            $table->string('user_agent', 255)
                  ->nullable()
                  ->comment('Navigateur / application');

            // Entité concernée
            $table->foreignUuid('entite_id')
                  ->nullable()
                  ->constrained('entites')
                  ->nullOnDelete()
                  ->comment('Entité concernée');

            // Date et heure de l'action
            $table->timestamp('created_at')
                  ->useCurrent()
                  ->comment('Date et heure exacte de l\'action');

            // Index optimisés pour requêtes fréquentes
            $table->index('user_id', 'idx_hist_user');
            $table->index('action', 'idx_hist_action');
            $table->index(['subject_type', 'subject_id'], 'idx_hist_subject');
            $table->index('entite_id', 'idx_hist_entite');
            $table->index('created_at', 'idx_hist_created_at');
            $table->index(['subject_type', 'subject_id', 'created_at'], 'idx_hist_subject_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_actions');
    }
};