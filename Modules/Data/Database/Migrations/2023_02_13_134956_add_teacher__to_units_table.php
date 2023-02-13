<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeacherToUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->integer('teacher_count')->nullable();
            $table->double('SPP')->nullable();
            $table->double('DP')->nullable();
            $table->double('DPP')->nullable();
            $table->double('UP')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('teacher_count');
            $table->dropColumn('SPP');
            $table->dropColumn('DP');
            $table->dropColumn('DPP');
            $table->dropColumn('UP');
        });
    }
}
