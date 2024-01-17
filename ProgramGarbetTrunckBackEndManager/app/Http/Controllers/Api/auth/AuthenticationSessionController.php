<?php

namespace App\Http\Controllers\Api\V1\auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AuthenticationSessionController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt($request->only('email','password'))){
            $user = $request->user();

            return response()->json([
                'token' =>$user->createToken($request->name)->plainTextToken,
                'name' => $user->name,
                'email'=> $user->email,
                'message' => 'Success'
            ],Response::HTTP_ACCEPTED);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);

        }
    }
    public function logout(Request $request){
        $user= $request->User();
        if($user){
            $user->currentAccessToken()->delete();
            return response()->json([
                'message'=> 'Successfully logged out'
            ],Response::HTTP_ACCEPTED);

        }
        else{
            return response()->json([
                'message'=> 'Successfully logged out'
            ],Response::HTTP_UNAUTHORIZED);
        }
    }


    public function register(Request $request){

        $user = new User();

        $user->name = $request->input('name');
        $user->password = $request->input('password');
        $user->email = $request->input('email');

        $user->save();

        return response()->json([
            'message' => 'User registered',
            'data'=>$user
        ], Response::HTTP_ACCEPTED);

    }
}
