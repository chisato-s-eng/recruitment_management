<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnApplicantIdToApplicantNameRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('records', function (Blueprint $table) {
            //
            $table->renameColumn('applicant_id', 'applicant_name');
        });

        Schema::table('records', function (Blueprint $table) {
            //
            $table->string('applicant_name')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('records', function (Blueprint $table) {
            //
            $table->renameColumn('applicant_name', 'applicant_id');
        });

        Schema::table('records', function (Blueprint $table) {
            //
            $table->foreignId('applicant_id')->constrauned()->change();
        });

    }
}
