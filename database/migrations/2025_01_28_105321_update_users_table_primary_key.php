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
        Schema::table('users', function (Blueprint $table) {
             // Supprimer la clé primaire existante sur `idUser`
             $table->dropPrimary(['idUser']);

             // Ajouter une nouvelle colonne `id` comme clé primaire auto-incrémentée
             $table->bigIncrements('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             // Supprimer la colonne `id`
             $table->dropColumn('id');

             // Restaurer `idUser` comme clé primaire
             $table->primary('idUser');
        });
    }
};
