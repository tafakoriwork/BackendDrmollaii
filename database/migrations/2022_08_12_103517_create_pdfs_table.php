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
        Schema::create('pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('url');
            $table->string('price')->default("0");
            $table->unsignedBigInteger('category_id');
            $table->text('desc')->nullable();
            $table->unsignedBigInteger('payment_id');
            $table->foreign('category_id')->on('pdfcategories')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('pdfs');
    }
};
