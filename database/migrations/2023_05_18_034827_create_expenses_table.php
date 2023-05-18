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
        $this->down();
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string("expenseid");
            $table->string("categoryid");
            $table->string("userid");
            $table->string("amount", 18, 2);
            $table->date("entrydate");
            $table->timestamps();
        });

        DB::table('expenses')->insert([
            [
                "expenseid" => Str::random(10),
                "categoryid" => "aZEfQwwtOf",
                "userid" => "Q1SN5ipWtn",
                "amount" => 230.00,
                "entrydate" => "2019-03-31",
                "created_at" => date('Y-m-d', strtotime('now'))
            ],[
                "expenseid" => Str::random(10),
                "categoryid" => "aZEfQwwtOf",
                "userid" => "q2ZzGiZ1Ry",
                "amount" => 120.00,
                "entrydate" => "2019-03-31",
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
        Schema::dropIfExists('expenses');
    }
};
