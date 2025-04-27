<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personnes', function (Blueprint $table) {
            $table->string('serologie')->default('Négatif');
            $table->dateTime('dernierDateDon')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('personnes', function (Blueprint $table) {
            $table->dropColumn(['serologie', 'dernierDateDon']);
        });
    }
};
