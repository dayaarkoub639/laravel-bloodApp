<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationFieldsToPersonneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personne', function (Blueprint $table) {
            $table->integer('wilaya_prof_id') ->nullable();
            $table->integer('commune_prof_id') ->nullable();
            $table->integer('wilaya_domicile_id') ->nullable() ;
            $table->integer('commune_domicile_id') ->nullable() ;
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personne', function (Blueprint $table) {
            $table->dropColumn([
                'wilaya_prof_id',
                'commune_prof_id',
                'wilaya_domicile_id',
                'commune_domicile_id' 
            ]);
        });
    }
}