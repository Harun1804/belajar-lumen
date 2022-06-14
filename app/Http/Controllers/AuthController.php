<?php

namespace App\Http\Controllers;

use App\Models\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

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
                try {

                    if (! $token = $this->jwt->attempt($request->all())) {
                        return response()->json(['user_not_found'], 404);
                    }

                    $data = ['token' => $token];

                    return response()->json([
                        'status' => 'success',
                        'message'=> '',
                        'data'   => $data,
                    ],200,[
                        'Authorization' => "Bearer $token"
                    ]);

                } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], 500);

                } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], 500);

                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent' => $e->getMessage()], 500);

                }

                // if(Hash::check($request->password, $user->password)){
                //     $apikey = base64_encode(Str::random(40));
                //     $user->update([
                //         'api_key' => $apikey
                //     ]);

                //     Hash::needsRehash($user->password);

                //     return response()->json([
                //         'status'  => 'success',
                //         'data'    => $user
                //     ], 200);
                // }else{
                //     return response()->json([
                //         'status'  => 'error',
                //         'message' => 'Password is incorrect'
                //     ], 400);
                // }
            }else{
                return response()->json([
                    'status'  => 'error',
                    'message' => "User not found"
                ], 404);
            }
        }
    }

    public function logout(Request $request)
    {
        $token =  $request->header('Authorization');
        if($token){
            $this->jwt->parseToken()->invalidate();
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Logout success'
        ], 200);
    }
}
