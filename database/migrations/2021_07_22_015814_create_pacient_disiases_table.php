<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientDisiasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacient_disiases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_enfermedad')->references('id')->on('disiases');
            $table->foreignId('id_paciente')->references('id')->on('pacients');
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
        Schema::dropIfExists('pacient_disiases');
    }
}
