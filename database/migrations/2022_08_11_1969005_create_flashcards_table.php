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
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();
            $table->text('front');
            $table->text('back');
            $table->unsignedBigInteger('order');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->on('units')->references('id')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->nullable()->on('users')->references('id')->onDelete('cascade');

            $table->unsignedBigInteger('freeflashcardscategory_id')->nullable();
            $table->foreign('freeflashcardscategory_id')->nullable()->on('freeflashcardscategory')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('flashcards');
    }
};
