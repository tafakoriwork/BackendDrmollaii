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
        Schema::create('takhfifs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('percent')->default(0);
            $table->integer('count')->default(1);
            $table
                ->foreignId('user_id')
                ->nullable()
                ->references('id')
                ->on('users');
            $table
                ->foreignId('group_id')
                ->nullable()
                ->references('id')
                ->on('tgroup');
            $table->date('expire_date');
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
        Schema::dropIfExists('takhfifs');
    }
};
