<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('majormains', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        DB::table('majormains')->insert([
            ['title' => 'پره انترنی و دستیاری',],
            ['title' => 'ارتقا بورد',],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('majormains');
    }
};
