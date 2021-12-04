<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchOutputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_output', function (Blueprint $table) {
            $table->string('record');
            $table->integer('si_company_id')->unsigned()->nullable();
            $table->integer('si_individual_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('si_company_id')
                ->references('id')
                ->on('search_input_company')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('si_individual_id')
                ->references('id')
                ->on('search_input_individual')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_output');
    }
}
