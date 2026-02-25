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
    Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('nom');
    $table->string('prenom');
    $table->string('email', 191)->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('poste')->nullable();
    $table->string('password');
    $table->string('signature', 255)->nullable();
    $table->uuid('entite_id')->nullable(); // relation entité
   $table->foreignUuid('role_id')->nullable()->constrained('roles')->nullOnDelete();
    $table->rememberToken();
    $table->timestamps();

  
    $table->foreign('entite_id')->references('id')->on('entites')->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::dropIfExists('users');
    }
};
