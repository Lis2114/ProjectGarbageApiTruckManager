<?php

namespace App\Http\Controllers\employee;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class employeeController extends Controller
{
    public function index()
    {
        $url = env('API_SERVER_IP');
        $response = Http::get($url . '/v1/employees');
        $employees = $response->json("data");


        return view('employee.indexEmployee', ['employees' => $employees]);
    }

    public function create()
    {
        $url = env('API_SERVER_IP');
        $response = Http::get($url . '/v1/employees');
        $employees = $response->json("data");

        return view('employee.CreateEmployee', ['employee' => $employees]);
    }

    public function store(Request $request)
    {
        $url = env('API_SERVER_IP');

        $request->validate([
            'name' => 'required|regex:/^([A-Za-zÑñ\s]*)$/|between:3,50',
            'lastName' => 'required|regex:/^([A-Za-zÑñ\s]*)$/|between:3,50',
            'identification' => 'required|integer|between:1000000,1500000000',
            'type' => 'required|in:Recolector,Barrendero,Conductor'
        ]);
        $response = Http::post($url . '/v1/employees',
            [
                'name' => $request->name,
                'lastName' => $request->lastName,
                'identification' => $request->identification,
                'type' => $request->type,
            ]);
        if ($response->successful()) {
            return redirect()->route('employee.index')->with(['message' => 'Empleado registrado']);
        } else {
            back()->withErrors([
                'message' => 'Error al registrar empleado'
            ]);
        }
        // return redirect()->route('employee.index');
    }
    public function show(Employee $employee)
    {
    }

    public function edit(int $employee)
    {
        $url = env('API_SERVER_IP');

        $response = Http::get($url . '/v1/employees/'. $employee);
        $employee = $response->json("data");

        return view('employee.UpdateEmployee', ['employee' => $employee]);
    }

    public function update(Request $request, int $employee)
    {
        $url = env('API_SERVER_IP');


        $request->validate([
            'name' => 'required|regex:/^([A-Za-záéíóúÁÉÍÓÚÑñ\s]*)$/|between:3,50',
            'lastName' => 'required|regex:/^([A-Za-záéíóúÁÉÍÓÚÑñ\s]*)$/|between:3,50',
            'identification' => 'required|integer|between:1000000,1500000000',
            'type' => 'required|in:Recolector,Barrendero,Conductor'
        ]);
       //dd($request);
        $response = Http::put($url . '/v1/employees/' . $employee,[
            'id'=>$employee,
            'name' => $request->name,
            'lastName' => $request->lastName,
            'identification' => $request->identification,
            'type' => $request->type
        ]);
        if ($response->successful()) {
            return redirect()->route('employee.index')->with(['message' => 'Empleado modificado']);
        } else {
            back()->withErrors([
                'message' => 'Error al modificar el empelado'
            ]);
        }
        // return redirect()->route('employee.index');
    }

    public function destroy(int $employee)
    {
        $url = env('API_SERVER_IP');

        $response = Http::delete($url . '/v1/employees/' . $employee);
        if ($response->successful()) {
            return redirect()->route('employee.index')->with(['message' => 'Empleado eliminado']);
        } else {
            back()->withErrors([
                'message' => 'Error al eliminar el Empleado'
            ]);
        }
    }
}
