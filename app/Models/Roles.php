<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    public $id = false;
    protected $fillable = [
        "roleid",
        "name",
        "role",
        "description",
    ];
}
