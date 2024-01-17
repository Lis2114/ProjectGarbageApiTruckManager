<?php

namespace App\Http\Controllers\truck;

use App\Models\Truck;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/trucks/');
        $trucks = $response->json("data");

        return view('truck.indexTruck',['trucks'=> $trucks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/trucks/');
        $trucks = $response->json("data");

        return view('truck.CreateTruck', ['trucks'=>$trucks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('API_SERVER_IP');


        $request->validate([
            'numberRegistration' => 'required|regex:/^([A-Za-zÑñ0-9\s\-]*)$/|between:1,7',
            'capacity' => 'required|integer|between:100,500',
        ]);

        $response = Http::post($url . '/v1/trucks/',
        [
            'numberRegistration' => $request->numberRegistration,
            'capacity' => $request->capacity
        ]);
        if ($response->successful()) {
            return redirect()->route('truck.index');
        } else {
            back()->withErrors([
                'message' => 'Error al registrar horario'
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $truck)
    {
        $url = env('API_SERVER_IP');
        $response = Http::get($url . '/v1/trucks/'.$truck);
        $truck = $response->json()["data"];

        return view('truck.UpdateTruck', ['truck' => $truck]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $truck)
    {
        $url = env('API_SERVER_IP');


        $request->validate([
            'numberRegistration' => 'required|regex:/^([A-Za-zÑñ0-9\s\-]*)$/|between:1,7',
            'capacity' => 'required|integer|between:100,500',
        ]);

        $response = Http::put($url . '/v1/trucks/'. $truck,[
            'id'=>$truck,
            'numberRegistration' => $request->numberRegistration,
            'capacity' => $request->capacity
        ]);

        if ($response->successful()) {
            return redirect()->route('truck.index')->with(['message' => 'Camion modificado']);
        } else {
            back()->withErrors([
                'message' => 'Error al modificar el camion'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $truck)
    {
        $url = env('API_SERVER_IP');
        $response = Http::delete($url . '/v1/trucks/' . $truck);
        //dd($response);
        if ($response->successful()) {
            return redirect()->route('truck.index')->with(['message' => 'Horario eliminado']);
        } else {
            back()->withErrors([
                'message' => 'Error al eliminar el horario'
            ]);
        }
    }
}
