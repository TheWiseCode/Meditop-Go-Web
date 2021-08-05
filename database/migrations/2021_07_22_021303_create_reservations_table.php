<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('total')->default(0);
            $table->dateTime('time_reservation')->default('now');
            $table->dateTime('time_consult');
            $table->enum('state', ['pendiente', 'rechazada', 'cancelada', 'aceptada'])
                ->default('pendiente');
            $table->foreignId('id_offer')->references('id')->on('offer_specialties');
            $table->foreignId('id_patient')->references('id')->on('patients');
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
        Schema::dropIfExists('reservations');
    }
}
