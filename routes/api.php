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
use App\Http\Controllers\FileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
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

Route::get('advanceplan/countrecognitions/{scoutid}', [AdvancePlanController::class, 'getRecognationsComplete']);
Route::get('emailvalidate/{email}', [AuthController::class, 'send']);
Route::get('codevalidate/{code}', [AuthController::class, 'validateCode']);
//Auth routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('signOut', [AuthController::class, 'signOut']);
Route::post('singIn', [AuthController::class, 'singIn']);


Route::get('advancePlan/{id}', [AdvancePlanController::class, 'recognitionsWithItems']);
Route::get('advancePlan/scout/{id}', [AdvancePlanController::class, 'recognitionsWithItemsByScout']);
Route::post('advancePlanUpdate', [AdvancePlanController::class, 'updateAdvancePlan']);
Route::get('advancePlanChecks/{scoutId}', [AdvancePlanController::class, 'getTopicsChecked']);

Route::resource('advancePlan', AdvancePlanController::class)->except([
	'edit', 'create'
]);

// Route for admin permissions
Route::group(['middleware'=>['auth:api', 'scopes:get-groups']],function() {

	Route::get('advanceplan/percent/{id}', [AdvancePlanController::class, 'percentAdvancePlan']);
	Route::get('advanceplan/countrecognitions/{scoutid}', [AdvancePlanController::class, 'getRecognationsComplete']);




	Route::post('validate', [AuthController::class, 'validateToken']);


	Route::get('profile', [ProfileController::class, 'show']);

	Route::post('file', [FileController::class, 'store']);
	Route::post('filePay', [FileController::class, 'storePay']);
	Route::post('fileGroup', [FileController::class, 'storeGroup']);
	Route::post('fileUnit', [FileController::class, 'storeUnit']);
	Route::post('fileAvatar', [FileController::class, 'storeAvatar']);


	Route::post('inscription/register', [InscriptionController::class, 'inscription']);
	Route::get('inscription/group', [InscriptionController::class, 'getScoutInscription']);
	Route::get('inscription/scout', [ProfileController::class, 'infoInscription']);
	Route::get('inscription', [InscriptionController::class, 'getAllInscriptions']);
	Route::post('updateInscription', [InscriptionController::class, 'putScoutInscription']);
	Route::post('checkadvanceplan', [AdvancePlanController::class, 'checkAdvancePlan']);

	
	Route::get('group/validate/{codigo}', [GroupController::class, 'validateGroup']);
	Route::get('group/activate/{groupId}', [GroupController::class, 'activate']);
	Route::resource('group', GroupController::class)->except([
		'edit', 'create'
	]);


	Route::resource('period', PeriodController::class);
	Route::get('nowperiod', [PeriodController::class, 'showPeriodNow']);

	Route::get('directingprofile', [DirectingController::class, 'directingProfile']);

	Route::resource('directing', DirectingController::class)->except([
		'edit', 'create'
	]);

	// Route::get('unit/directing/table', [UnitController::class, 'showToDirecting']);
	Route::get('unit/activate/{unitId}', [UnitController::class, 'activate']);
	Route::get('unit/directing', [UnitController::class, 'indexByDirecting']);
	Route::get('unit/scout/{scoutid}', [UnitController::class, 'showByScout']);
	Route::get('unit/group/{group}', [UnitController::class, 'showByGroup']);
	Route::resource('unit', UnitController::class)->except([
		'edit', 'create'
	]);
	



	Route::resource('scout', ScoutController::class)->except([
		'edit', 'create'
	]);
	Route::get('scout/info/{scoutId}', [ScoutController::class, 'getData']);

	Route::get('scout/group/{scout}', [ScoutController::class, 'showByGroup']);
	// Route::get('scout/unit/{scout}', [ScoutController::class, 'showByUnit']);
	Route::get('scout/validate/{scoutid}', [ScoutController::class, 'validateTeam']);
	Route::get('scout/unit/{unitid}', [ScoutController::class, 'scoutsByUnit']);
	Route::get('scoutsbydirectings', [ScoutController::class, 'scoutsByDirecting']);
	
	Route::resource('user', UserController::class)->except([
		'edit', 'create'
	]);
	

	Route::resource('role', RoleController::class);

	Route::resource('team', TeamController::class);
	Route::get('teams/directing', [TeamController::class, 'teamByDirecting']);
	Route::get('team/unit/{unitid}', [TeamController::class, 'getByUnit']);
	Route::post('team/scout/', [TeamController::class, 'teamScout']);
});



