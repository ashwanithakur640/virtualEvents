<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name',10);
            $table->string('middle_name',10)->nullable();
            $table->string('last_name',10)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('country_code',10);
            $table->string('country_iso',10);
            $table->string('mobile',16);
            $table->string('company_name',50)->nullable();
            $table->string('company_city_location',30)->nullable();
            $table->string('state',20)->nullable();
            $table->string('country',20)->nullable();
            $table->string('address',100)->nullable();
            $table->string('website',100)->nullable();
            $table->string('office_no',20)->nullable();
            $table->string('office_email_id',50)->nullable();
            $table->string('image',191)->nullable();
            $table->enum('status',['Active','Inactive','Not-verified'])->default('Active')->comment('Active, Inactive, Not-verified');
            $table->enum('role_id',['0','1','2'])->comment('0 => Admin , 1 => Vendor , 2 => User');
            $table->integer('deleted_is_admin')->nullable()->comment('1 => Deleted Is Admin');
            $table->string('verify_token',100)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
