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
        Schema::create('demande_personne', function (Blueprint $table) {
          
            $table->unsignedBigInteger('personne_id');
            $table->unsignedBigInteger('demande_id');
            $table->timestamp('date_acceptation')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->primary(['personne_id', 'demande_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_personne');
    }
};
