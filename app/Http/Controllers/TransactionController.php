<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = [];
        $employeesInfo = DB::table('transactions')
                            //->whereColumn('is_payed', false);/*
                            ->join('employees', 'transactions.employee_id', '=', 'employees.id')
                            ->select('employee_id', 'employee_hour_rate', DB::raw('sum(hours) as total_hours'), 'is_payed')
                            ->groupBy('employee_id', 'employee_hour_rate', 'is_payed')
                            ->orderBy('employee_id')
                            ->get();


            foreach ($employeesInfo as $employeeInfo) {
                !$employeeInfo->is_payed ? $result[] = [(int) $employeeInfo->employee_id => (float) $employeeInfo->total_hours * (float) $employeeInfo->employee_hour_rate] : null;
            }
        

        /*foreach ($employees as $employee) {
            // не нравится делать так много запросов к бд.
            $employeeWorkedHours = Transaction::all()->where('employee_id', $employee->id)->sum('hours');
            //$employeeWorkedHours
            if ($employeeWorkedHours > 0) {
            $result[$employee->id] = $employeeWorkedHours;
            }
        }*/
        return response()->json($result);
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
            'employee_id' => 'required|integer',
            'hours' => 'required|numeric|max:24|min:0.5',
            'transaction_date' => 'nullable|date' // нужно для тестов
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Status:' => "Fail",
                'Info:' => "{$validator->errors()}"
            ]);
        }

        /**
         * здесь проверяем свои ли часы пытается добавить сотрудник
         */
        $employee = Auth::user();
        if ($employee->id !== (int) $request->employee_id) {
            return response()->json([
                'Status:' => 'Fail!',
                'Info' => 'Cannot add the working time for another employee'
            ]);
        }

        $requestDate = (date("Y-m-d", $request->server->get('REQUEST_TIME')));
        $validatedData = $validator->validated();
        $transaction = new Transaction();
        $transaction->fill($validatedData);

        // проверка для тестов. По умолчанию поле transaction_date пустое и заполняется датой запроса.
        if (is_null($transaction->transaction_date)) {
            $transaction->transaction_date = $requestDate;
        }

        $transactionInDB = Transaction::where('employee_id', $request->employee_id)->where('transaction_date', $transaction->transaction_date/*'like', $request->transactionDate.'%'*/)->first();

        if ($transactionInDB) {
            return response()->json([
                'Status:' => 'Fail!',
                'Info' => 'You have already sent your working hours!'
            ]);
        }

        $transaction->save();

        return response()->json([
            'Status:' => 'Success!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $transactions = Transaction::whereIs_payed(false)->update(['is_payed' => true]);
        if ($transactions == 0) {
            return response()->json([
                'Status:' => "Fail",
                'Info:' => "Nothing to pay"
            ]);
        }
        return response()->json([
            'Status:' => 'Success!'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
