<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_operations', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_operacion');
            $table->string('descripcion');
            $table->date('fecha');
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
        Schema::dropIfExists('history_operations');
    }
}
