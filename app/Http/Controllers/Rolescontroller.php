<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Roles;

use DB;
use Str;

class Rolescontroller extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function processRole(Request $request){
        $request = $request->all();
        if(empty($request['name']) && $request['type'] != "delete"){
            return response()->json(['status' => false, 'msg' => "Name is empty!"]);
        }
        if(empty($request['description']) && $request['type'] != "delete"){
            return response()->json(['status' => false, 'msg' => "Description is empty!"]);
        }

        DB::beginTransaction();
        try{

            $msg = "";
            
            if($request['type'] == "delete" || $request['type'] == "update"){
                $check = Roles::where('roleid', $request['roleid'])->first();
                if($check->role == "administrator"){
                    return response()->json(['status' => false, 'msg' => "Unable to ".$request['type']."!"]);
                }
            }
            if($request['type'] == "add"){
                Roles::create([
                    "roleid" => Str::random(10),
                    "name" => $request['name'],
                    "role" => "user",
                    "description" => $request['description'],
                ]);
                $msg = "added!";

            }else if($request['type'] == "update"){
                Roles::where('roleid', $request['roleid'])->update([
                    "name" => $request['name'],
                    "description" => $request['description'],
                ]);
                $msg = "updated!";

            }else if($request['type'] == "delete"){
                Roles::where('roleid', $request['roleid'])->delete();
                $msg = "deleted!";
            }


            DB::commit();
            return response()->json(['status' => true, 'msg' => "Role ".$msg]);
        }catch(\Exception | \Error $ex){
            DB::rollback();
            // return $ex;
            return response()->json(['status' => false, 'msg' => "Something went wrong!"]);
        }
    }

    public function getRoleInfo(Request $request){
        $data = Roles::where('roleid', $request->input('roleid'))->first();
        return response()->json([
            "name" => $data->name,
            "description" => $data->description,
            "role" => $data->role,
            "roleid" => $data->roleid
        ]);
    }

    public function fetchRoles(){
        $data = Roles::all();
        $output = '';
        foreach($data as $row){
            $output .= '
            <tr id="showModal" data-type="update" data-roleid="'.$row['roleid'].'">
                <td>'.$row['name'].'</td>
                <td>'.$row['description'].'</td>
                <td>'.date('Y-m-d', strtotime($row['created_at'])).'</td>
            </tr>
            ';
        }
        return $output;
    }
}
