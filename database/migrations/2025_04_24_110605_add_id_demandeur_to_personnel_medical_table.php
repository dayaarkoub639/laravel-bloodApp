<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personnelMedical', function (Blueprint $table) {
            $table->unsignedBigInteger('idDemandeur')->nullable()->after('id');

            // Si tu veux ajouter une contrainte de clé étrangère :
            // $table->foreign('idDemandeur')->references('id')->on('personnes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('personnelMedical', function (Blueprint $table) {
            $table->dropColumn('idDemandeur');
        });
    }
};
