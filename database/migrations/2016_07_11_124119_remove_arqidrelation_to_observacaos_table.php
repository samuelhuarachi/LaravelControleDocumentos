<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveArqidrelationToObservacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('observacaos', function (Blueprint $table) {
            $table->dropForeign(['arq_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('observacaos', function (Blueprint $table) {
            $table->foreign('arq_id')->references('id')->on('documentos')->onDelete('cascade');
        });
    }
}
