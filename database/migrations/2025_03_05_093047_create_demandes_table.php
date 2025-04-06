<?php

// Migration pour la table 'demandes'
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandesTable extends Migration
{
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id('id');
            $table->date('dateDemande');
            $table->string('status');
            $table->string('lieuDemande');
            $table->string('serviceMedical');
            $table->string('groupageDemande');
            $table->integer('quantiteDemande');
            $table->string('urgence');
            $table->string('typeMaladie');
            $table->string('idDemandeur');
            $table->string('numeroDossierMedical')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes');
    }
}
