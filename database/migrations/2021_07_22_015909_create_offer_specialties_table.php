<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferSpecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_days');//Abstraccion se guardará la inicial separada con |
            //EJM: L|M|X|J|V|S|D|
            $table->time('schedule_time');
            $table->foreignId('id_doctor')->references('id')->on('doctors');
            $table->foreignId('id_disease')->references('id')->on('specialties');
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
