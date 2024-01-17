<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthenticationSessionController extends Controller
{
    public function create(){
        return view('auth.Login');
    }

    public function store(Request $request)
    {
        $url = env('API_SERVER_IP');

        $request->validate(
            [
                'email' => 'required|string|email|max:255|min:8',
                'password' => 'required|string'
            ]
        );

        $response = Http::post($url . '/login', [
            'email' => $request->email,
            'password' => $request->password,
            'name' => 'browser',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // dd($data);
            $request->session()->put('api_token', $data['token']);
            $request->session()->put('user_name', $data['name']);
            $request->session()->put('user_email', $data['email']);

            //Crear el archivo de la sesión
            //Almacenando datos mientras esta en la sesión
            $request->session()->regenerate();

            // dd($request->session());

            return redirect()->route('welcome');
        } else {
            // back()->withErrors([
            //     'message' => 'Credenciales invalidas'
            // ]);

            return redirect()->route('login')->withErrors(['message' => 'Credenciales invalidas']);
        }
    }

    public function destroy(Request $request)
    {
        $url = env('API_SERVER_IP');

        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $request->session()->get('api_token')])->post($url . '/logout');

        if ($response->successful()) {
            $request->session()->forget('api_token');
            //Destruir el archivo de sesión
            $request->session()->invalidate();
            //Obtener un nuevo token
            $request->session()->regenerateToken();

            return redirect()->route('login');
        } else {
            // back()->withErrors([
            //     'message' => 'Error al cerrar sesión'
            // ]);

            return redirect()->route('login')->withErrors(['message' => 'Error al cerrar sesión']);
        }
    }
}
