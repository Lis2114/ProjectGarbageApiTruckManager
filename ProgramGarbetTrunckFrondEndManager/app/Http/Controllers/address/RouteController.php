<?php

namespace App\Http\Controllers\address;

use App\Models\Path;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/routes/');
        $routes = $response->json("data");

        return view('address.indexRoute', ['routes' => $routes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/routes/');
        $routes = $response->json("data");


        return view('address.CreateRoute',[ 'routes' => $routes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $url = env('API_SERVER_IP');

        $request->validate([
            'sector' => 'required|regex:/^([A-Za-zÑñ\s]*)$/|between:3,50',
            'neighborhoods' => 'required|regex:/^([A-Za-zÑñ\s0-9]*)$/|between:3,50',
        ]);

        $response = Http::post($url . '/v1/routes/',
        [
            'sector' => $request->sector,
            'neighborhoods' => $request->neighborhoods
        ]);

        if ($response->successful()) {
            return redirect()->route('route.index');
        } else {
            back()->withErrors([
                'message' => 'Error al registrar rutas'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Path $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $route)
    {

        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/routes/');
        $route = $response->json("data");

        return view('address.UpdateRoute', ['route' => $route]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $route)
    {

        $url = env('API_SERVER_IP');


        $request->validate([

            'sector' => 'required|regex:/^([A-Za-zÑñ\s]*)$/|between:3,50',
            'neighborhoods' => 'required|regex:/^([A-Za-zÑñ\s]*)$/|between:3,50',
        ]);


        $response = Http::put($url . '/v1/routes/'. $route,
        [
            'id' =>$route,
            'sector' => $request->sector,
            'neighborhoods' => $request->neighborhoods
        ]);

        if ($response->successful()) {
            return redirect()->route('route.index')->with(['message' => 'Ruta modificada']);
        } else {
            back()->withErrors([
                'message' => 'Error al modificar el rutas'
            ]);
        }

    }

    public function destroy(int $route)
    {
        $url = env('API_SERVER_IP');
        $response = Http::delete($url . '/v1/routes/'. $route);

        if ($response->successful()) {
            return redirect()->route('route.index')->with(['message' => 'Ruta eliminada']);
        } else {
            back()->withErrors([
                'message' => 'Error al eliminar la ruta'
            ]);
        }
    }
}
