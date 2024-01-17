<?php

namespace App\Http\Controllers\schedule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ScheduleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $url = env('API_SERVER_IP');
        $response = Http::get($url .'/v1/schedules');
        $schedules = $response->json("data");

        $response = Http::get($url . '/v1/paths');
        $paths = $response->json("data");

        $response = Http::get($url . '/v1/employees');
        $employees = $response->json("data");

        $response = Http::get($url . '/v1/trucks');
        $trucks = $response->json("data");

        return view('schedules.indexSchedules', ['schedules' => $schedules , 'paths' => $paths, 'employees'=>$employees , 'trucks' =>$trucks],);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/routes');
        $paths = $response->json("data");

        $response = Http::get($url . '/v1/employees');
        $employees = $response->json("data");

        $response = Http::get($url . '/v1/trucks');
        $trucks = $response->json("data");

        return view('schedules.CreateSchedules', ['paths' => $paths, 'employees' => $employees, 'trucks' => $trucks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('API_SERVER_IP');

        $response = Http::post($url . '/v1/schedules',

            [
                'hourExit' => $request->hourExit,
                'hourArrival' => $request->hourArrival,
                'date' => $request->date,
                'route_id' => $request->route,
                'truck_id' => $request->truck,
                'employee_id' => $request->employee

            ]
        );

        if ($response->successful()) {
            return redirect()->route('schedule.index')->with(['message' => 'Horario registrado']);
        } else {
            back()->withErrors([
                'message' => 'Error al registrar horario'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($schedule)
    {

        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($schedule)
    {
        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/routes');
        $paths = $response->json("data");

        $response = Http::get($url . '/v1/employees');
        $employees = $response->json("data");

        $response = Http::get($url . '/v1/trucks');
        $trucks = $response->json("data");

        return view('schedules.UpdateSchedules', ['schedule' => $schedule, 'paths' => $paths, 'employees' => $employees, 'trucks' => $trucks]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $schedule)
    {

        $url = env('API_SERVER_IP');
        $response = Http::put($url . '/v1/schedules/' . $schedule,
        // $response = Http::put($this->url . '/v1/schedules/' . $schedule,
            [
                'hourExit' => $request->hourExit,
                'hourArrival' => $request->hourArrival,
                'date' => $request->date,
                'route_id' => $request->route,
                'truck_id' => $request->truck,
                'employee_id' => $request->employee

            ]
        );

        if ($response->successful()) {
            return redirect()->route('schedule.index')->with(['message' => 'Horario modificado']);
        } else {
            back()->withErrors([
                'message' => 'Error al modificar el horario'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($schedule)
    {
        $url = env('API_SERVER_IP');

        $response = Http::delete($url . '/v1/schedules/' . $schedule);

        // $response = Http::delete($this->url . '/v1/schedules/' . $schedule);

        if ($response->successful()) {
            return redirect()->route('schedule.index')->with(['message' => 'Horario eliminado']);
        } else {
            back()->withErrors([
                'message' => 'Error al eliminar el horario'
            ]);
        }
    }
}
