<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertSpecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ofert_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('dias_horario');//Abstraccion se guardará la inicial separada con |
            //EJM: L|M|X|J|V|S|D|
            $table->time('horario');
            $table->foreignId('id_doctor')->references('id')->on('doctors');
            $table->foreignId('id_enfermedad')->references('id')->on('specialties');
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
        Schema::dropIfExists('ofert_specialties');
    }
}
