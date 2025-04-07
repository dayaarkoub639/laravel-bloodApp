<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCompagnesTable extends Migration
{
    public function up()
    {
        Schema::table('compagnes', function (Blueprint $table) {
            // Suppression de la colonne 'localisation'
            $table->dropColumn('localisation');

            // Ajout des nouvelles colonnes
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->time('heureDebut')->nullable();
            $table->time('heureFin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('compagnes', function (Blueprint $table) {
            // Restauration de la colonne 'localisation'
            $table->string('localisation')->nullable();

            // Suppression des nouvelles colonnes
            $table->dropColumn(['latitude', 'longitude', 'heureDebut', 'heureFin']);
        });
    }
}
