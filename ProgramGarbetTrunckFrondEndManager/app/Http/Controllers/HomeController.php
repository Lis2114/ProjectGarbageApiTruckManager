<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/schedules');
        if ($response->successful()) {
            $schedules = $response->json('data');
            
            return view('home.Index', ['schedules' => $schedules]);
        } else {
            back()->withErrors([
                'message' => 'Error al obtener las rutas'
            ]);
        }
    }
}
