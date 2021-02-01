<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('Vendor, Customer');
            $table->foreign('user_id')->references('id')->on('users')->onSoftDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onSoftDelete('cascade')->onUpdate('cascade');
            $table->string('charge_id');
            $table->decimal('amount',10,2)->default(0);
            $table->string('currency');
            $table->string('balance_transaction');
            $table->string('validity',20)->comment('Purchased, Expire');
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
        Schema::dropIfExists('payments');
    }
}
