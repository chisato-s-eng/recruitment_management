<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReclitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reclites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('department_id')->constrauned();
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
        Schema::dropIfExists('reclites');
    }
}
