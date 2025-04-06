<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->integer('id')->primary()->unsigned(); // Clé primaire
            $table->string('acces', 255)->nullable(); // Champ supplémentaire
            $table->string('type');
            $table->timestamps();

            
           // $table->foreign('idUser')->references('idUser')->on('personne')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
