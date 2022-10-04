<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('phonenumber')->unique();
            $table->string('password');
            $table->text('api_token')->nullable();
            $table->string('api_ip1')->nullable();
            $table->string('api_ip2')->nullable();
            $table->string('api_ip3')->nullable();
            $table->string('uniqid')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
            $table->bigInteger('limit')->default(0);
        });
        

        DB::table('users')->insert([
            [
                'fname' => 'super',
                'lname' => 'admin',
                'phonenumber' => '09376722000',
                'password' => Hash::make('123456789'),
                'is_admin' => 1,
                'limit' => -100000000000
            ],
            [
                'fname' => 'super',
                'lname' => 'admin2',
                'phonenumber' => '09367750913',
                'password' => Hash::make('123456789'),
                'is_admin' => 1,
                'limit' => -100000000000
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
