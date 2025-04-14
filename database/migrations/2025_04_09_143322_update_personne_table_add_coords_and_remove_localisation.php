<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('centres', function (Blueprint $table) {
            $table->dropColumn('localisation');
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
        });
    }

    public function down()
    {
        Schema::table('centres', function (Blueprint $table) {
            $table->string('localisation')->nullable();
            $table->dropColumn(['longitude', 'latitude']);
        });
    }
};
