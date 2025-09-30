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
            $table->string('denomination');
            $table->string('nom_fournisseur'); // <- champ ajouté
            $table->uuid('entite_id');
            $table->string('reference_dp')->unique();
            $table->decimal('montant_ht', 15, 2)->default(0); // Montant hors taxe
            $table->decimal('tva', 5, 2)->default(0);         // TVA en pourcentage
            $table->decimal('montant_paiement_fournisseur', 15, 2)->default(0); // TTC calculé côté serveur
            $table->date('date_paiement');
            $table->string('contact_fournisseur', 20);
            $table->string('adresse_fournisseur');
            $table->string('email_fournisseur');
            $table->string('reference_facture')->nullable();
            $table->string('reference_bon_commande')->nullable();
            $table->string('reference_contrat')->nullable();
            $table->string('reference_expression_besoin')->nullable();
            $table->string('code_fournisseur')->nullable();
            $table->text('description')->nullable();
            $table->string('code_analytique')->nullable();
            $table->string('centre_analytique')->nullable();
            $table->string('code_projet')->nullable();
            $table->enum('priorite', ['normal', 'urgent', 'tres_urgent'])->default('normal');
            $table->string('pieces_jointes')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->uuid('user_id');
             // Dates de validation
            $table->timestamp('date_validation_controleur')->nullable();
            $table->timestamp('date_validation_daf')->nullable();
            $table->timestamp('date_validation_dg')->nullable();

            $table->timestamps();

            // Clés étrangères
            $table->foreign('entite_id')->references('id')->on('entites')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
