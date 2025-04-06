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
        Schema::create('centres', function (Blueprint $table) {
            $table->bigIncrements('id'); // Clé primaire auto-incrémentée
            $table->string('nom');
            $table->string('address');
            $table->integer('wilaya'); // Wilaya en INT
            $table->integer('commune'); // Commune en INT
            $table->text('localisation')->nullable();
            $table->string('numeroTlp1');
            $table->string('numeroTlp2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centres');
    }
};
