<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_medicine')->references('id')->on('medicines');
            $table->foreignId('id_prescription')->references('id')->on('prescriptions');
            $table->string('detail')->nullable();
            $table->unsignedInteger('schedule')->nullable();
            $table->unsignedInteger('days')->nullable();
            $table->unsignedInteger('amount')->nullable();
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
        Schema::dropIfExists('treatments');
    }
}
