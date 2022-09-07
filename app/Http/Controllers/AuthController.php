<?php

namespace App\Http\Controllers;

use App\Mail\ValidationAccount;
use Illuminate\Http\Request;
use  App\Models\User;
use  App\Models\Mode;
use  App\Models\Range;
use  App\Models\Validationmail;
use  App\Models\Person;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
	public function validateToken(Request $request)
	{
		try {
			$user = Auth::user();
			$userData = User::find($user->id);
			$profile = Person::find($user->person_id);
			$role = $userData->roles()->first();
			return response()->json([
				'user' => [
					'name' => $user->name,
					'email' => $user->email,
					'role' => $role->id,
					'nameRole' => $role->nombre,
					'avatar' => $profile->image
				],
				'token' => $request->token,
				'success' => 1
			]);
		} catch (Exception $e) {
			return response()->json([
				'success' => 2,
				'message' => $e
			]);
		}
	}

	public function login(Request $request)
	{
		try {
			$response = array('response' => '', 'success' => false);

			$validator = Validator::make($request->all(), [
				'email' => 'required|email',
				'password' => 'required',
			], $messages = [
				'required' => 'Verifique sus datos',
			]);
			if ($validator->fails()) {
				$response['response'] = $validator->messages();
			}
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
						'role' => $role->id,
						'nameRole' => $role->nombre
					],
					200
				);
			} else {
				return response()->json(
					[
						'error' => 'Verifique sus credenciales',
						'success' => 0
					],
					200
				);
			}
		} catch (Exception $e) {
			return response()->json([
				'error' => $e
			]);
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
				'type_blood' => '',
				'phone' => '',
				'gender' => $request->gender,
				'image' => '',
				'nacionality' => 'Ecuador'
			]);
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->dni),
			'person_id' => $idPerson
		]);
		$age = getAge($request->bornDate);
		$type = setRange($age, Range::all());
		$scoutId = DB::table('scouts')
			->insertGetId([
				'person_id' => $idPerson,
				'type' => $type
			]);
		$user->roles()->attach(6);



		return response()->json([
			'scout' => $scoutId,
			'success' => 1
		]);
	}

	public function validateCode($code)
	{
		$validation = Validationmail::where('code', $code)->where('state', 'A')->first();
		$validation->update([
			'state' => 'D'
		]);
		$validation->save();
		return response()->json(
			[
				'success' => 1,
				'val' => $validation
			]
		);
	}

	public function send($email)
	{
		$date = date('m-d-Y h:i:s a', time());
		$hash = substr(hash('ripemd160', $email . $date), 0, 5);
		Validationmail::create([
			'code' => $hash,
			'email' => $email,
			'state' => 'A'
		]);
		Mail::to($email)->send(new ValidationAccount('Tu codigo de validacion es: ' . $hash));
		return true;
	}

}
