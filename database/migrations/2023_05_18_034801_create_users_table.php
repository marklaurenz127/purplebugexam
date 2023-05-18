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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('userid', 100);
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('password', 100);
            $table->string('role', 100);
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                "userid" => "Q1SN5ipWtn",
                "name" => "Jaun Dela Cruz",
                "email" => "juan@expensemanager.com",
                "password" => "0000",
                "role" => "administrator",
                "created_at" => date('Y-m-d', strtotime('now'))
            ],[
                "userid" => "q2ZzGiZ1Ry",
                "name" => "Leo Ocampo",
                "email" => "leo@expensemanager.com",
                "password" => "0000",
                "role" => "user",
                "created_at" => date('Y-m-d', strtotime('now'))
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
