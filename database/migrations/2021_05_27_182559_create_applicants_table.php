<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('address', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('mobile_phone', 15)->nullable();
            $table->string('home_phone', 15)->nullable();
            $table->foreignId('department_id')->constrauned();
            $table->foreignId('reclite_id')->constrauned();
            $table->foreignId('user_id')->constrauned();
            $table->integer('status')->length(1);
            $table->string('memo', 255)->nullable();
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
        Schema::dropIfExists('applicants');
    }
}
