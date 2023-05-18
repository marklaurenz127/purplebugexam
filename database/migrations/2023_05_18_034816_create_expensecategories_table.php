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
        Schema::create('expensecategories', function (Blueprint $table) {
            $table->id();
            $table->string('categoryid', 100);
            $table->string('name', 100);
            $table->string('desc', 100);
            $table->timestamps();
        });

        DB::table('expensecategories')->insert([
            [
                "categoryid" => "aZEfQwwtOf",
                "name" => "Travel",
                "desc" => "daily commute",
                "created_at" => date('Y-m-d', strtotime('now'))
            ],[
                "categoryid" => Str::random(10),
                "name" => "Entertainment",
                "desc" => "movies etc.",
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
        Schema::dropIfExists('expensecategories');
    }
};
