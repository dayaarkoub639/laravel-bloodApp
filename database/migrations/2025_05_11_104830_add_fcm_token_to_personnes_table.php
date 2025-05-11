<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('personnes')) {
            Schema::table('personnes', function (Blueprint $table) {
                if (!Schema::hasColumn('personnes', 'fcm_token')) {
                    $table->string('fcm_token')->nullable();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('personnes') && 
            Schema::hasColumn('personnes', 'fcm_token')) {
            Schema::table('personnes', function (Blueprint $table) {
                $table->dropColumn('fcm_token');
            });
        }
    }
};