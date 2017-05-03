<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('ticket_id');
            $table->timestamps();
            $table->string('owner_first_name');
            $table->string('owner_last_name');
            $table->integer('user_id');
            $table->string('event');
            $table->string('artist');
            $table->float('price');
            $table->string('venue');
            $table->string('city');
            $table->dateTime('date_event');
            $table->string('unique_id');
            $table->string('image')->default('defaultTicket.png');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
