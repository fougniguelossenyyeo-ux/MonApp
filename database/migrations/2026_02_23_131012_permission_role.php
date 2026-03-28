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
        Schema::create('permission_role', function (Blueprint $table) {
            $table->uuid('role_id');                   // Clé étrangère vers roles
            $table->uuid('permission_id');  
    
            $table->timestamps();

            // Définition des clés étrangères
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->nullOnDelete();

            // Empêcher les doublons
            $table->unique(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
};