<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirectingController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ScoutController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Auth routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'adminRegister']);

Route::resource('group', GroupController::class)->except([
	'edit', 'create'
]);
Route::resource('directing', DirectingController::class)->except([
	'edit', 'create'
]);
Route::get('unit/group/{group}', [UnitController::class, 'showByGroup']);
Route::resource('unit', UnitController::class)->except([
	'edit', 'create'
]);
// Route for admin permissions
Route::group(['middleware'=>['auth:api', 'scope:get-groups']],function() {

	Route::resource('user', UserController::class)->except([
		'edit', 'create'
	]);


	Route::get('scout/group/{scout}', [ScoutController::class, 'showByGroup']);
	Route::get('scout/unit/{scout}', [ScoutController::class, 'showByUnit']);
	Route::resource('scout', ScoutController::class)->except([
		'edit', 'create'
	]);


});

