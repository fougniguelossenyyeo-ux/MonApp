<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('demmandes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->string('denomination');
             $table->string('entite_id');
            $table->decimal('montant_paiement_fournisseur', 15, 2);
            $table->date('date_paiement');
            $table->string('contact_fournisseur', 20);
            $table->text('adresse_fournisseur');
            $table->string('email_fournisseur');
            
            $table->string('reference_facture');
            $table->string('reference_bon_commande');
            $table->string('reference_contrat');
            $table->string('reference_expression_besoin');
            $table->string('code_fournisseur');
            
            $table->text('description');
            $table->string('code_analytique');
            $table->string('centre_analytique');
            $table->string('code_projet');
            $table->string('priorite');
            
            $table->string('pieces_jointes');
            
            $table->timestamps();
        });
          Schema::table('demmandes', function (Blueprint $table) {
            $table->foreign('entite_id')->references('id')->on('entites')->onDelete('cascade');
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demmandes');
    }
};
