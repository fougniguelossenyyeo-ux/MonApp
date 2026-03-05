<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Lien unique vers la demande (1:1)
            $table->foreignUuid('demande_id')
                  ->unique()                        // ← IMPORTANT : empêche plusieurs paiements par demande
                  ->constrained('demandes')
                  ->onDelete('restrict')            // empêche suppression demande si paiement existe
                  ->comment('Une seule ligne paiement par demande');

            // Responsable du paiement
            $table->foreignUuid('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Comptable / caissier qui gère ce paiement');

            // Montants globaux du paiement
            $table->decimal('montant_prevu', 15, 2)
                  ->comment('Montant total à payer = montant_paiement_fournisseur de la demande');

            $table->decimal('montant_paye', 15, 2)
                  ->default(0)
                  ->comment('Cumul des versements effectués');

            $table->decimal('montant_restant', 15, 2)
                  ->default(0)
                  ->comment('montant_prevu - montant_paye (peut être calculé)');

            // Statut global du paiement
            $table->enum('statut', [
                'en_attente',       // paiement pas encore lancé
                'initie',           // ordre de paiement créé
                'partiel',          // au moins un versement effectué
                'termine',          // totalement payé
                'annule',           // annulé avant exécution
                'echec',            // rejet / erreur
            ])->default('en_attente');

            // Informations traçabilité (dernière opération ou globale)
            $table->string('mode_paiement', 60)->nullable()
                  ->comment('Mode du dernier versement ou principal');

            $table->string('reference_paiement', 120)->nullable()
                  ->comment('Référence globale ou du dernier versement');

            $table->date('date_paiement_effectif')->nullable()
                  ->comment('Date du dernier versement ou date de paiement complet');

            $table->text('notes')->nullable();

            $table->timestamps();
           
            // Index
            $table->index('demande_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};