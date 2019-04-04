<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('distance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('start_lat');
            $table->string('start_long');
            $table->string('end_lat');
            $table->string('end_long');
            $table->integer('distance'); //Distance in meters
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
        Schema::dropIfExists('distance');
    }
}
