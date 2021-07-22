<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consults', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechahora');
            $table->string('estado');
            $table->string('url_consulta');
            $table->unsignedInteger('min_duration');
            $table->foreignId('id_doctor')->references('id')->on('doctors');
            $table->foreignId('id_paciente')->references('id')->on('pacients');
            $table->foreignId('id_reservacion')->references('id')->on('reservations');
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
        Schema::dropIfExists('consults');
    }
}
