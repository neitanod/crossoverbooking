<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenueMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_maps', function (Blueprint $table) {
          // Stand disposition changes from one event to the next
          // so the map does not belong to a venue but to an event
            $table->increments('id');
            $table->string('name');
            $table->integer('event_id');
            $table->longText('svgdata');
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
        Schema::drop('venue_maps');
    }
}
