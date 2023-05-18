<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    public $id = true;
    protected $fillable = [
        "userid",
        "name",
        "email",
        "password",
        "role",
    ];
}
