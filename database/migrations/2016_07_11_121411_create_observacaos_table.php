<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObservacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observacaos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('arq_id')->unsigned();
            $table->foreign('arq_id')->references('id')->on('documentos')->onDelete('cascade');
            $table->text('observacao');
            $table->integer('ativo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('observacaos');
    }
}
