<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dons', function (Blueprint $table) {
            $table->string('sourceDon')->nullable()->after('reactions');
        });
    }

    public function down(): void
    {
        Schema::table('dons', function (Blueprint $table) {
            $table->dropColumn('sourceDon');
        });
    }
};
