<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->integer('year')->nullable(false);
            $table->integer('month')->nullable(false);
            $table->integer('day')->nullable(false);
            $table->integer('hour')->nullable(false);
            $table->integer('minute')->nullable(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
            $table->dropColumn('day');
            $table->dropColumn('hour');
            $table->dropColumn('minute');
        });
    }
}
