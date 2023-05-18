<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Expensecategories;
use App\Models\Expenses;

use Str;
use DB;

class Expensecontroller extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function getUserID(){
        return session()->get('usersession');
    }

    public function getAllExpenses(){
        $data = [];

        $categories = Expensecategories::all();
        foreach($categories as $row){
            $sub = [];
            $sub['category'] = $row['name'];
            $sub['total'] = Expenses::where('categoryid', $row['categoryid'])->sum('amount');
            $data[] = $sub;
        }

        return $data;
    }

    public function getCategoryInfo(Request $request){
        $data = Expensecategories::where('categoryid', $request->input('categoryid'))->first();
        return response()->json([
            "name" => $data->name,
            "desc" => $data->desc,
            "categoryid" => $data->categoryid,
        ]);
    }

    public function fetchCategory(){
        $data = Expensecategories::all();
        $output = '';
        foreach($data as $row){
            $output .= '
            <tr id="showModal" data-type="update" data-categoryid="'.$row['categoryid'].'">
                <td>'.$row['name'].'</td>
                <td>'.$row['desc'].'</td>
                <td>'.date('Y-m-d', strtotime($row['created_at'])).'</td>
            </tr>
            ';
        }
        return $output;
    }

    public function processCategory(Request $request){
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
            if($request['type'] == "add"){
                Expensecategories::create([
                    "categoryid" => Str::random(10),
                    "name" => $request['name'],
                    "desc" => $request['description']
                ]);
                $msg = " added!";
            }else if($request['type'] == "update"){
                Expensecategories::where('categoryid', $request['categoryid'])->update([
                    "name" => $request['name'],
                    "desc" => $request['description']
                ]);
                $msg = " updated!";
            }else if($request['type'] == "delete"){
                Expensecategories::where('categoryid', $request['categoryid'])->delete();
                $msg = " deleted!";
            }

            DB::commit();
            return response()->json(['status' => true, 'msg' => "Category ".$msg]);
        }catch(\Exception | \Error $ex){
            DB::rollback();
            return $ex;
            return response()->json(['status' => false, 'msg' => "Something went wrong!"]);
        }

    }

    public function fetchExpenses(){
        $data = Expenses::select('expenses.amount','expenses.entrydate','expenses.created_at','expenses.expenseid','expensecategories.name')
        ->join('expensecategories','expensecategories.categoryid','expenses.categoryid')
        ->where('expenses.userid', $this->getUserID())
        ->get();
        $output = '';
        foreach($data as $row){
            if(count($data) > 0){
                $output .= '
                <tr id="showModal" data-type="update" data-expenseid="'.$row['expenseid'].'">
                    <td>'.$row['name'].'</td>
                    <td>&#36; '.number_format($row['amount'], 2).'</td>
                    <td>'.date('Y-m-d', strtotime($row['entrydate'])).'</td>
                    <td>'.date('Y-m-d', strtotime($row['created_at'])).'</td>
                </tr>
                ';
            }else{
                $output = '
                    <tr>
                        <td class="text-center" colspan="4">No expenses found</td>
                    </tr>
                ';
            }

        }
        return $output;
    }

    public function processExpense(Request $request){
        $request = $request->all();

        if($request['type'] != "delete"){
            if($request['categoryid'] == "-"){
                return response()->json(['status' => false, 'msg' => "Select a category!"]);
            }
            if((empty($request['amount']) || !is_numeric($request['amount']) || $request['amount'] <= 0)){
                return response()->json(['status' => false, 'msg' => "Invalid amount!"]);
            }
            if(empty($request['entrydate'])){
                return response()->json(['status' => false, 'msg' => "Emtry date is empty!"]);
            }
            $date = strtotime($request['entrydate']);
            if($date > time()){
                return response()->json(['status' => false, 'msg' => "Invalid entry date!"]);
            }
        }
        
        DB::beginTransaction();
        try{
            $msg = "";
            if($request['type'] == "add"){
                Expenses::create([
                    "expenseid" => Str::random(10),
                    "categoryid" => $request['categoryid'],
                    "userid" => $this->getUserID(),
                    "amount" => $request['amount'], 
                    "entrydate" => $request['entrydate'],
                ]);
                $msg = " added!";
            }else if($request['type'] == "update"){
                Expenses::where('expenseid', $request['expenseid'])->update([
                    "categoryid" => $request['categoryid'],
                    "amount" => $request['amount'], 
                    "entrydate" => $request['entrydate'],
                ]);
                $msg = " updated!";
            }else if($request['type'] == "delete"){
                Expenses::where('expenseid', $request['expenseid'])->delete();
                $msg = " deleted!";
            }

            DB::commit();
            return response()->json(['status' => true, 'msg' => "Category ".$msg]);
        }catch(\Exception | \Error $ex){
            DB::rollback();
            return $ex;
            return response()->json(['status' => false, 'msg' => "Something went wrong!"]);
        }
    }

    public function getExpenseInfo(Request $request){
        $data = Expenses::where('expenseid', $request->input('expenseid'))->first();
        $categories = Expensecategories::all();
        $output = '';
        $output = '
        <div class="form-group">
            <label for="">Expense Category</label>
            <select id="category" class="form-control">
            <option value="-"> -- Select -- </option>';
                foreach($categories as $row){
                    if($row['categoryid'] == $data->categoryid){
                        $output .= '<option value="'.$row['categoryid'].'" selected>'.$row['name'].'</option>';
                    }else{
                        $output .= '<option value="'.$row['categoryid'].'">'.$row['name'].'</option>';
                    }
                }
        $output .= '
            </select>
        </div>
        <div class="form-group">
            <label for="">Amount</label>
            <input type="number" class="form-control" id="amount" value="'.$data->amount.'">
        </div>
        <div class="form-group">
            <label for="">Entry Date</label>
            <input type="date" class="form-control" id="entrydate" value="'.date('m/d/Y', strtotime($data->entrydate)).'">
        </div>
        <input type="hidden" id="expid" value="'.$data->expenseid.'">
        ';
        return $output;
    }
}
