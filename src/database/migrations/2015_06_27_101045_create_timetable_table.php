<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('for_id')->unsigned()->index();
            $table->string('for_type', 128);
            $table->integer('timezone');
            $table->unique(array('for_id','for_type'));
        });

        Schema::create('timetable_days', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('timetable_id')->unsigned();
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('day', array(
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THURSDAY',
                'FRIDAY',
                'SATURDAY',
                'SUNDAY'
            ));
            $table->time("from");
            $table->time("to");
        });

        Schema::create('timetable_specifics', function(Blueprint $table) {
            $table->integer('timetable_id')->unsigned();
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('restrict')->onUpdate('cascade');
            $table->boolean('is_available')->default(0);
            $table->dateTime('from');
            $table->dateTime('to');
            $table->primary('timetable_id');
        });

        Schema::create('timetable_bookings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('timetable_id')->unsigned();
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('restrict')->onUpdate('cascade');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->integer('for_id')->unsigned()->index();
            $table->string('for_type', 128);
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
        Schema::dropIfExists('timetable_bookings');
        Schema::dropIfExists('timetable_specifics');
        Schema::dropIfExists('timetable_days');
        Schema::dropIfExists('timetables');
    }
}
