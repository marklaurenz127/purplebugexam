<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Pagecontroller;
use App\Http\Controllers\Rolescontroller;
use App\Http\Controllers\Userscontroller;
use App\Http\Controllers\Expensecontroller;
use App\Http\Controllers\Authcontroller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/',[Pagecontroller::class, 'index']);
Route::group(['middleware' => ['usersession']], function(){
    Route::get('/roles',[Pagecontroller::class, 'roles']);
    Route::get('/users',[Pagecontroller::class, 'users']);
    Route::get('/expense categories',[Pagecontroller::class, 'expenseCat']);
    Route::get('/expenses',[Pagecontroller::class, 'expense']);
    Route::get('/change password',[Pagecontroller::class, 'changePass']);
});
//Auth
Route::post('/login',[Authcontroller::class, 'login']);
Route::get('/logout',[Authcontroller::class, 'logout']);
///Roles
Route::post('/admin/processRole',[Rolescontroller::class, 'processRole']);
Route::post('/admin/getRoleInfo',[Rolescontroller::class, 'getRoleInfo']);
Route::post('/admin/fetchRoles',[Rolescontroller::class, 'fetchRoles']);
//User
Route::post('/admin/processUser',[Userscontroller::class, 'processUser']);
Route::post('/admin/getUserInfo',[Userscontroller::class, 'getUserInfo']);
Route::post('/admin/fetchUsers',[Userscontroller::class, 'fetchUsers']);
Route::post('/user/changePassword',[Userscontroller::class, 'changePassword']);
//Category
Route::post('/admin/processCategory',[Expensecontroller::class, 'processCategory']);
Route::post('/admin/getCategoryInfo',[Expensecontroller::class, 'getCategoryInfo']);
Route::post('/admin/fetchCategory',[Expensecontroller::class, 'fetchCategory']);
//Expenses
Route::post('/admin/processExpense',[Expensecontroller::class, 'processExpense']);
Route::post('/admin/getExpenseInfo',[Expensecontroller::class, 'getExpenseInfo']);
Route::post('/admin/fetchExpenses',[Expensecontroller::class, 'fetchExpenses']);
Route::post('/admin/getAllExpenses',[Expensecontroller::class, 'getAllExpenses']);

