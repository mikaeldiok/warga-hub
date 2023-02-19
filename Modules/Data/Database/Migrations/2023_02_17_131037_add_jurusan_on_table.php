<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJurusanOnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subunits', function (Blueprint $table) {
            $table->string('use_jurusan')->nullable();
            $table->text('jurusan_name')->nullable();
            $table->text('jurusan_count')->nullable();
        });

        Schema::table('generations', function (Blueprint $table) {
            $table->string('use_jurusan')->nullable();
            $table->text('jurusan_name')->nullable();
            $table->text('jurusan_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subunits', function (Blueprint $table) {

            $table->dropColumn('use_jurusan');
            $table->dropColumn('jurusan_name');
            $table->dropColumn('jurusan_count');
        });

        Schema::table('generations', function (Blueprint $table) {

            $table->dropColumn('use_jurusan');
            $table->dropColumn('jurusan_name');
            $table->dropColumn('jurusan_count');
        });
    }
}
