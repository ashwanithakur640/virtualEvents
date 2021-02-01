<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModratorControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modrator_controls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conf_id'); 
            $table->foreign('conf_id')->references('id')->on('session')->onSoftDelete('cascade')->onUpdate('cascade');
            $table->integer('mic')->nullable();
            $table->integer('chat')->nullable();
            $table->integer('video')->nullable();
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
        Schema::dropIfExists('modrator_controls');
    }
}
