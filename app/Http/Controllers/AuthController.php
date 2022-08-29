<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User;
use  App\Models\Scout;
use  App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
	public function validateToken(Request $request)
	{
		$user = Auth::user();
		$userData = User::find($user->id);
		$profile = Person::find($user->person_id);
		$role = $userData->roles()->first();
		return response()->json([
			'user' => [
				'name' => $user->name,
				'email' => $user->email,
				'role' => $role->id,
				'avatar' => $profile->image
			],
			'token' => $request->token
		]);
	}

	public function login(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		$credentials = $request->only(['email', 'password']);

		if (Auth::attempt($credentials)) {
			$user = $request->user();
			$userData = User::find($user->id);
			$role = $userData->roles()->first();
			// $user = Auth::user();
			// $success['token'] = $user->createToken('get-groups')->accessToken;
			$success['token'] = $user->createToken('MyApp', ['*'])->accessToken;

			return response()->json(
				[
					'token' => $success,
					'success' => 1, 
					'user' => $user,
					'role' => $role->id
				],
				200
			);
		} else {
			return response()->json(['error' => 'Unauthorized'], 401);
		}
	}

	public function register(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
			'c_password' => 'required|same:password',
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);

		$success['name'] = $user->name;
		$success['token'] = $user->createToken('get-groups')->accessToken;
		return response()->json(['success' => $success], 200);
	}

	public function adminLogin(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		$credentials = $request->only(['email', 'password']);

		if (Auth::attempt($credentials)) {
			$user = $request->user();
			// $user = Auth::user();
			$success['token'] = $user->createToken('MyApp', ['*'])->accessToken;
			return response()->json(['token' => $success, 'success' => 1, 'user' => $user], 200);
		} else {
			return response()->json(['error' => 'Unauthorized'], 401);
		}
	}

	public function adminRegister(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
			'c_password' => 'required|same:password',
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);
		$success['name'] = $user->name;
		$success['token'] = $user->createToken('Sudo', ['get-groups'])->accessToken;
		return response()->json(['success' => $success], 200);
	}

	public function singIn(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'email' => 'required|email'
		]);
		$idPerson = DB::table('persons')
			->insertGetId([
				'name' => $request->name,
				'last_name' => $request->lastName,
				'dni' => $request->dni,
				'born_date' => $request->bornDate,
				'type_blood' => $request->typeBlood,
				'phone' => $request->phone,
				'gender' => $request->gender,
				'image' => '',
				'nacionality' => 'Ecuador'
			]);
		User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->dni),
			'person_id' => $idPerson
		]);

		$scoutId = DB::table('scouts')
			->insertGetId([
				'person_id' => $idPerson,
				'type' => ''
			]);



		return response()->json([
			'scout' => $scoutId,
			'success' => 1
		]);
	}
}
