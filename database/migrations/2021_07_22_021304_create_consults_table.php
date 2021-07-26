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
            $table->dateTime('time');
            $table->enum('state', ['cancelada', 'aceptada'])
                ->default('aceptada');
            $table->string('url_jitsi');
            $table->unsignedInteger('min_duration');
            $table->foreignId('id_doctor')->references('id')->on('doctors');
            $table->foreignId('id_patient')->references('id')->on('patients');
            $table->foreignId('id_reservation')->references('id')->on('reservations');
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
