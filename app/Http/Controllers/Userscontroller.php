<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Users;
use App\Models\Roles;

use DB;
use Str;
use Hash;

class Userscontroller extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    function getUserID(){
        return session()->get('usersession');
    }

    public function fetchUsers(){
        $data = Users::all();
        $output = '';
        foreach($data as $row){
            $output .= '
            <tr id="showModal" data-type="update" data-userid="'.$row['userid'].'">
                <td>'.$row['name'].'</td>
                <td>'.$row['email'].'</td>
                <td>'.$row['role'].'</td>
                <td>'.date('Y-m-d', strtotime($row['created_at'])).'</td>
            </tr>
            ';
        }
        return $output;
    }

    public function getUserInfo(Request $request){
        $data = Users::where('userid', $request['userid'])->first();
        $roles = Roles::all();
        $output = '';
        $output = '
        <div class="form-group">
            <label for="">Name</label>
            <input type="text" class="form-control" id="name" value="'.$data->name.'">
        </div>
        <div class="form-group">
            <label for="">Email Address</label>
            <input type="text" class="form-control" id="email" value="'.$data->email.'">
        </div>
        <div class="form-group">
            <label for="">Role</label>
            <select id="role" class="form-control">';
                foreach($roles as $row){
                    if($row['role'] == $data->role){
                        $output .= '<option value="'.$row['roleid'].'" selected>'.$row['name'] .'</option>';
                    }else{
                        $output .= '<option value="'.$row['roleid'].'">'.$row['name'] .'</option>';
                    }
                }
        $output .= '    </select>
        </div>
        <input type="hidden" id="userid" value="'.$data->userid.'">
        ';
        return response()->json([
            "output" => $output,
            "user_role" => $data->role
        ]);
    }

    public function processUser(Request $request){
        $request = $request->all();
        if(empty($request['name']) && $request['type'] != "delete"){
            return response()->json(['status' => false, 'msg' => "Name is empty!"]);
        }
        if(empty($request['email']) && $request['type'] != "delete"){
            return response()->json(['status' => false, 'msg' => "Email is empty!"]);
        }
        

        DB::beginTransaction();
        try{
            $role = Roles::where('roleid', $request['roleid'])->first();
            $msg = "";

            if($request['type'] == "add"){
                $check_email = Users::where('email', $request['email'])->first();
                if(!empty($check_email)){
                    return response()->json(['status' => false, 'msg' => "Email already used!"]);
                }
                Users::create([
                    "userid" => Str::random(10),
                    "name" => $request['name'],
                    "email" => $request['email'],
                    "password" => "0000",
                    "role" => $role->role,
                ]);
                $msg = "added!";
            }

            if($request['type'] == "delete" || $request['type'] == "update"){
                $user = Users::where('userid', $request['userid'])->first();
                if($user->role == "administrator"){
                    return response()->json(['status' => false, 'msg' => "Unable to ".$request['type']."!"]);
                }
                
                if($request['type'] == "update"){
                    $check_email = Users::where('email', $request['email'])->first();
                    if(!empty($check_email)){
                        if($check_email->userid != $user->userid){
                            return response()->json(['status' => false, 'msg' => "Email already used!"]);
                        }
                    }
                    Users::where('userid', $request['userid'])->update([
                        "name" => $request['name'],
                        "email" => $request['email'],
                        "role" => $role->role,
                    ]);
                    $msg = "updated!";
                }else if($request['type'] == "delete"){
                    Users::where('userid', $request['userid'])->delete();
                    $msg = "deleted!";
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'msg' => "User ".$msg]);
        }catch(\Exception | \Error $ex){
            DB::rollback();
            // return $ex;
            return response()->json(['status' => false, 'msg' => "Something went wrong!"]);
        }
    }

    public function changePassword(Request $request){
        DB::beginTransaction();
        try{

            Users::where('userid', $this->getUserID())->update([
                "password" => $request->input('password')
            ]);

            DB::commit();
            return response()->json(['status' => true, 'msg' => "Updated"]);
        }catch(\Exception | \Error $ex){
            DB::rollback();
            // return $ex;
            return response()->json(['status' => false, 'msg' => "Something went wrong!"]);
        }
    }
}
