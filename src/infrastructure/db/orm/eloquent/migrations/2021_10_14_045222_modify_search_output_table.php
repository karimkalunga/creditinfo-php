<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySearchOutputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('search_output', function(Blueprint $table) {
            $table->dropConstrainedForeignId('si_company_id');
            $table->dropConstrainedForeignId('si_individual_id');
            $table->text('record')->change();
            $table->id()->after('record');
            $table->string('applicant_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('search_output', function(Blueprint $table) {
            $table->integer('si_company_id')->unsigned()->nullable();
            $table->integer('si_individual_id')->unsigned()->nullable();
            $table->string('record');
        });
    }
}
