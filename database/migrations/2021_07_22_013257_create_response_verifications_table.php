<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_verifications', function (Blueprint $table) {
            $table->id();
            $table->dateTime('time');
            $table->boolean('response');
            $table->string('detail');
            $table->foreignId('id_verification')->references('id')->on('verifications');
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
        Schema::dropIfExists('response_verifications');
    }
}
