<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;


class RegisterUserController extends Controller
{
    public function create(){
        return view('auth.Register');
    }

    public function store( Request $request )
    {
        $url = env('API_SERVER_IP');

        // $request-> validate([
        //     'name' => 'required|string|max:255|min:8',
        //     'email'=> 'required|string|email|max:255|min:8|unique:users',
        //     'password'=> ['required','confirmed',Password::default()],
        // ]);

        $response = Http::post($url . '/register',[
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        if ($response->successful()) {
            dd($response);
            return redirect()->route('login')->with(['message' => 'Usuario registrado']);
        } else {
            back()->withErrors([
                'message' => 'Error al registrar usuario'
            ]);
        }

    }
}
