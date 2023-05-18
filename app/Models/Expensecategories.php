<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expensecategories extends Model
{
    public $id = false;
    protected $fillable = [
        "categoryid",
        "name",
        "desc",
    ];
}
