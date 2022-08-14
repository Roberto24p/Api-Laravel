<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirectingController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ScoutController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\AdvancePlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeriodController;
use Monolog\Handler\GroupHandler;

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
Route::post('register', [AuthController::class, 'register']);
Route::post('signOut', [AuthController::class, 'signOut']);
Route::post('singIn', [AuthController::class, 'singIn']);


Route::get('advancePlan/{id}', [AdvancePlanController::class, 'recognitionsWithItems']);
Route::post('advancePlanUpdate', [AdvancePlanController::class, 'updateAdvancePlan']);

Route::resource('advancePlan', AdvancePlanController::class)->except([
	'edit', 'create'
]);

// Route for admin permissions
Route::group(['middleware'=>['auth:api', 'scopes:get-groups']],function() {

	Route::get('profile', [ProfileController::class, 'show']);

	Route::get('group/validate/{codigo}', [GroupController::class, 'validateGroup']);
	Route::post('inscription/register', [InscriptionController::class, 'inscription']);
	Route::get('inscription/group', [InscriptionController::class, 'getScoutInscription']);
	Route::get('inscription/scout', [ProfileController::class, 'infoInscription']);
	Route::post('checkadvanceplan', [AdvancePlanController::class, 'checkAdvancePlan']);
	Route::resource('group', GroupController::class)->except([
		'edit', 'create'
	]);
	Route::resource('period', PeriodController::class);
	
	Route::resource('directing', DirectingController::class)->except([
		'edit', 'create'
	]);
	Route::get('unit/group/{group}', [UnitController::class, 'showByGroup']);
	Route::resource('unit', UnitController::class)->except([
		'edit', 'create'
	]);
	
	Route::resource('scout', ScoutController::class)->except([
		'edit', 'create'
	]);
	Route::resource('user', UserController::class)->except([
		'edit', 'create'
	]);
	Route::get('scout/group/{scout}', [ScoutController::class, 'showByGroup']);
	Route::get('scout/unit/{scout}', [ScoutController::class, 'showByUnit']);


});



