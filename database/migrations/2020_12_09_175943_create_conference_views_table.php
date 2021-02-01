<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConferenceViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conference_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conf_id');
            $table->foreign('conf_id')->references('id')->on('session')->onSoftDelete('cascade')->onUpdate('cascade');
             $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onSoftDelete('cascade')->onUpdate('cascade');
            $table->enum('type',['0','1'])->default('1');;
            $table->longText('comment');
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
        Schema::dropIfExists('conference_views');
    }
}
