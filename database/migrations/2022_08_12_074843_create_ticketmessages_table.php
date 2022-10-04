<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketmessages', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->boolean('is_response')->default(false);
            $table->unsignedBigInteger('ticket_id');
            $table->foreign('ticket_id')->on('tickets')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('ticketmessages');
    }
};
