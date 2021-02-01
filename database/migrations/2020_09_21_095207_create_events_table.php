<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('Admin, Vendor');
            $table->foreign('user_id')->references('id')->on('users')->onSoftDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onSoftDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('title');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->string('type',10);
            $table->decimal('amount',10,2)->default(0);
            $table->longText('description');
            $table->enum('status',['Active','Inactive','Onhold','Cancelled'])->default('Active')->comment('Active, Inactive, Onhold, Cancelled');
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
