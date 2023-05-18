<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Users;

use Hash;

class Authcontroller extends Controller
{
    public function login(Request $request){
        $request = $request->all();
        if(empty($request['email'])){
            return response()->json(['status' => false, 'msg' => "Email is empty!"]);
        }
        if(empty($request['password'])){
            return response()->json(['status' => false, 'msg' => "Password is empty!"]);
        }

        $checkAccount = Users::where('email', $request['email'])->first();
        if(empty($checkAccount)){
            return response()->json(['status' => false, 'msg' => "Account not found!"]);
        }else{
            if($request['password'] == $checkAccount->password){
                session()->put('usersession', $checkAccount->userid);
                session()->put('role', $checkAccount->role);
                session()->put('name', $checkAccount->name);
                return response()->json(['status' => true]);
            }else{
                return response()->json(['status' => false, 'msg' => "Incorrect password!"]);
            }
        }
    }

    public function logout(){
        session()->flush();
        return redirect("/");
    }
}
