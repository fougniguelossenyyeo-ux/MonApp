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
            
            // Lien avec la demande
            $table->uuid('demande_id');
            

            // Montants
            $table->decimal('montant_deja_paye', 15, 2)->default(0);
            $table->decimal('montant_a_payer', 15, 2)->default(0);
            $table->decimal('montant_restant', 15, 2)->default(0);
            
            // Statut du paiement : 0 = non initié, 1 = en cours, 2 = partiellement payé, 3 = payé
            $table->tinyInteger('status_paiement')->default(0);

            $table->timestamps();

            // Clé étrangère
            $table->foreign('demande_id')->references('id')->on('demandes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
