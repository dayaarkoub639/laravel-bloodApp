<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personne', function (Blueprint $table) {
            $table->increments('idUser'); // Clé primaire
            $table->string('NIN', 20)->nullable();
            $table->string('numeroTlp1', 15)->nullable();
            $table->string('numeroTlp2', 15)->nullable();
            $table->integer('adresse')->nullable();
            $table->string('motDePasse', 255)->nullable();
            $table->date('dateDeNess')->nullable();
            $table->string('nom', 255)->nullable();
            $table->string('prenom', 255)->nullable();
            $table->string('gender', 255);
            $table->enum('typePersonne', ['user', 'admin', 'personnelMedical']);
            $table->integer('idPhenotype')->nullable();
            $table->integer('idGroupage')->nullable();
            $table->timestamps(); // Colonnes created_at et updated_at

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personne');
    }
}
