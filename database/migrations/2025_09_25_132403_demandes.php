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
            $table->uuid('entite_id');
            $table->decimal('montant_paiement_fournisseur', 15, 2);
            $table->date('date_paiement');
            $table->string('contact_fournisseur', 20);
            $table->text('adresse_fournisseur');
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
            $table->string('priorite')->default('normal');
            $table->string('pieces_jointes')->nullable();

            $table->tinyInteger('status')->default(0); 
            // 0 = créé, 1 = validé par Contrôleur, 2 = validé par DAF, 3 = validé par DG, -1 = refusé
            $table->uuid('created_by'); 
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            $table->foreign('entite_id')->references('id')->on('entites')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }  

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
