<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use  App\Models\RecoverPassword;
use  App\Models\Validationmail;
use App\Mail\ValidationAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class RecoverPasswordController extends Controller
{
    public function validateEmail($email)
    {
        $user = User::where('email', $email)->first();
        if($user == null){
            return response()->json([
                'success'=> 0,
                'message'=> 'Correo no registrado'
            ]);
        }
        $date = date('m-d-Y h:i:s a', time());
        $hash = substr(hash('ripemd160', $email . $date), 0, 5);
        RecoverPassword::create([
            'token' => $hash,
            'email' => $email,
            'state' => 'A'
        ]);
        Mail::to($email)->send(new ValidationAccount('Tu codigo de recuperación es: ' . $hash));
        return response()->json([
            'success' => 1,
        ]);
    }

    public function validateCode($code)
    {
        $validate = RecoverPassword::where('token', $code)->where('state', 'A')->first();
        if($validate != null){
            return response()->json([
                'success' => 1
            ]);
        }else{
            return response()->json([
                'success' => 0
            ]);
        }
    }

    public function recoverPassword(Request $request)
    {
        $validateCode = RecoverPassword::where('token', $request->code)->where('state', 'A')->first();
        if($validateCode!=null){
            $user = User::where('email', $validateCode->email)->first();
            $user->update([
                'password'=> Hash::make($request->password)
            ]);
            $user->save();
            $validateCode->update([
                'state' => 'D'
            ]);
            $validateCode->save();
            return response()->json([
                'success' => 1,
                'message' => 'Contraseña actualizada'
            ]);
        }else{
            return response()->json([
                'success' => 0,
                'message' => 'Problema validando, intentelo de nuevo'
            ]);
        }
    }
}
