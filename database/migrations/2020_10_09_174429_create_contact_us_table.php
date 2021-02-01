<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name',10);
            $table->string('middle_name',10)->nullable();
            $table->string('last_name',10)->nullable();
            $table->unsignedBigInteger('user_id')->comment('Vendor, Customer');
            $table->foreign('user_id')->references('id')->on('users')->onSoftDelete('cascade')->onUpdate('cascade');
            $table->string('email');
            $table->string('country_code',10);
            $table->string('country_iso',10);
            $table->string('mobile',20);
            $table->longText('description');
            $table->longText('answer')->nullable()->comment('by Admin');
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
        Schema::dropIfExists('contact_us');
    }
}
