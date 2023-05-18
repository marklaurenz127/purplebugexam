<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Roles;
use App\Models\Users;
use App\Models\Expensecategories;
use App\Models\Expenses;

class Pagecontroller extends Controller
{

    public function getUserID(){
        return session()->get('usersession');
    }

    public function index(){
        $data = [];

        $categories = Expensecategories::all();
        foreach($categories as $row){
            $sub = [];
            $sub['category'] = $row['name'];
            $sub['total'] = Expenses::where('categoryid', $row['categoryid'])->sum('amount');
            $data[] = $sub;
        }

        return view('index',[
            "data" => $data
        ]);
    }

    public function users(){
        return view('users',[
            "users" => Users::all(),
            "roles" => Roles::all()
        ]);
    }

    public function roles(){
        return view('roles',[
            "roles" => Roles::all()
        ]);
    }

    public function expenseCat(){
        return view('category',[
            "data" => Expensecategories::all()
        ]);
    }

    public function expense(){
        return view('expense',[
            "data" => Expenses::select('expenses.amount','expenses.entrydate','expenses.created_at','expenses.expenseid','expensecategories.name')
            ->join('expensecategories','expensecategories.categoryid','expenses.categoryid')
            ->where('expenses.userid', $this->getUserID())
            ->get(),
            "categories" => Expensecategories::all()
        ]);
    }

    public function changePass(){
        return view('changepass');
    }
}
