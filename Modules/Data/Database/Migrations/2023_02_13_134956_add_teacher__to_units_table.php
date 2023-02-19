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
            $table->integer('staff_count')->nullable();
            $table->boolean('use_generation')->nullable();
            $table->boolean('use_jurusan')->nullable();
        });

        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('unit_id');
            $table->string('name_or_jurusan');
            $table->double('SPP')->nullable();
            $table->double('DP')->nullable();
            $table->double('DPP')->nullable();
            $table->double('UP')->nullable();
            $table->timestamps();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->softDeletes();
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
            $table->dropColumn('staff_count');
            $table->dropColumn('use_generation');
        });

        Schema::dropIfExists('fees');
    }
}
