<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Informations principales
            $table->string('denomination');
            $table->string('reference_dp')->unique();

            // Liens clés étrangères
            $table->foreignUuid('entite_id')->constrained('entites')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            // Montants
            $table->decimal('montant_ht', 15, 2)->default(0); // Hors taxe
            $table->enum('tva', ['TVA 18%', 'TVA 0%', 'TVA sur hydrocarbure 9%'])->default('TVA 18%');
            $table->decimal('montant_paiement_fournisseur', 15, 2)->default(0); // TTC

            // Informations fournisseur
            $table->string('contact_fournisseur', 30);
            $table->string('adresse_fournisseur');
          $table->string('email_fournisseur', 191);
            $table->string('reference_facture')->nullable();
            $table->string('reference_bon_commande')->nullable();
            $table->string('reference_contrat')->nullable();
            $table->string('reference_expression_besoin')->nullable();
            $table->string('code_fournisseur')->nullable();

            // Analytique / projet
            $table->string('code_analytique')->nullable();
            $table->string('centre_analytique')->nullable();
            $table->string('code_projet')->nullable();

            // Pièces jointes
            $table->text('pieces_jointes')->nullable();

            // Statut général de la demande
            $table->tinyInteger('status')->default(0); // 0=Créé, 1=Contrôleur validé, 2=DAF validé, 3=DG validé

            // Dates de validation
            $table->timestamp('date_validation_controleur')->nullable();
            $table->timestamp('date_validation_daf')->nullable();
            $table->timestamp('date_validation_dg')->nullable();

            $table->text('description')->nullable(); // Optionnel, notes ou détails supplémentaires

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
