<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:employees',
            'password' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Status:' => "Fail",
                'Info:' => "{$validator->errors()}"
            ]);
        }

        $validatedData = $validator->validated();
        $email = $validatedData['email'];
        $password = $validatedData['password'];
        $validatedData['password'] = Hash::make($validatedData['password']);
        $employee = new Employee();
        $employee->fill($validatedData);
        $employee->employee_hour_rate = 200;
        $employee->save();


        return response()->json([
            'Status:' => 'Success!',
            'Message' => "Your id is {$employee->id}"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
