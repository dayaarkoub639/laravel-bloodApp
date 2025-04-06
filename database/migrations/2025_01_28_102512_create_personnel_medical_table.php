<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelMedicalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnelMedical', function (Blueprint $table) {
            $table->integer('idUser')->primary()->unsigned(); // Clé primaire
            $table->string('role', 255)->nullable();
            $table->timestamps(); // Colonnes created_at et updated_at
                 // Définir la clé étrangère
       //   $table->foreign('idUser')->references('idUser')->on('personne')->onDelete('cascade');
          
  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personnelMedical');
    }
}
