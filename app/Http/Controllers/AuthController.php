<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], 400);
        }else{
            $user = User::whereEmail($request->email)->first();
            if($user){
                if(Hash::check($request->password, $user->password)){
                    $apikey = base64_encode(Str::random(40));
                    $user->update([
                        'api_key' => $apikey
                    ]);

                    Hash::needsRehash($user->password);

                    return response()->json([
                        'status'  => 'success',
                        'data'    => $user
                    ], 200);
                }else{
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Password is incorrect'
                    ], 400);
                }
            }else{
                return response()->json([
                    'status'  => 'error',
                    'message' => "User not found"
                ], 404);
            }
        }
    }
}
