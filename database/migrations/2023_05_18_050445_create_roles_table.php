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
        // $this->down();
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('roleid', 100);
            $table->string('role', 100);
            $table->string('name', 100);
            $table->string('description', 100);
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [
                "roleid" => Str::random(10),
                "role" => "administrator",
                "name" => "Administrator",
                "description" => "Super user",
                "created_at" => date('Y-m-d', strtotime('now'))
            ],[
                "roleid" => Str::random(10),
                "role" => "user",
                "name" => "User",
                "description" => "can add expenses",
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
        Schema::dropIfExists('roles');
    }
};
