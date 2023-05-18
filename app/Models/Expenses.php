<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    public $id = false;
    protected $fillable = [
        "expenseid",
        "categoryid",
        "userid",
        "amount",
        "entrydate",
        "created_at",
    ];
}
