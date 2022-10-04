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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('merchant');
            $table->timestamps();
        });

        DB::table('payments')->insert([
            [
                'title' => 'درگاه ۱',
                'merchant' => 'f74597b0-b6e9-47d6-b08f-eed9fba57e9c',
            ],
            [
                'title' => 'درگاه ۲',
                'merchant' => '544ba264-3155-11ea-92ac-000c295eb8fc',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
