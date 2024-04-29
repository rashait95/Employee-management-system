<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\updateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee=Employee::all();
        return response()->json([
            'status'=>'success',
            'employees'=>$employee
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        try{
            DB::beginTransaction();

            $employee
            = Employee::create([
                'first_name'    =>$request->first_name,
                'last_name'     =>$request->last_name,
                'email'         =>$request->email,
                'position'      =>$request->position,
                'department_id' =>$request->department_id,
            ]);


            DB::commit();

           return response()->json(
            ['status'=>'employee created successfuly',
            'employee'=> $employee
            ,]
            );



        }
        catch(\Throwable  $th){

                DB::rollback();
                Log::error($th);
                return response()->json([
                    'status'=>'employee not created',
                    'error'=>$th->getMessage(),
                    
                ], 500);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return response()->json([
            'status'=>'success',
            'employee'=> $employee,
          ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateEmployeeRequest $request, Employee $employee)
    {

        
     dd($request);
        $employee->first_name = $request->get('first_name');
        $employee->last_name = $request->get('last_name');
        $employee->email = $request->get('email');
        $employee->position = $request->get('position');
        $employee->department_id  = $request->get('department_id');
        $employee -> save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json([
         'status'=>'employee deleted succesfully',
         'employees'=>$employee
        ]);
    }


    public function restore(string $id)
    {   
        $employee=Employee::withTrashed()->where('id', $id)->restore();

       // return response()->json(['department' => $department], 400);
        return response()->json(['message' => 'Employee restored']);
    }

    public function forceDelete(string $id)
    {
        $employee=Employee::withTrashed()->where('id', $id)->forceDelete();

         return response()->json(['message' => 'Employee deleted']);
        }

    public function showSoftDeletedEmployees()
{
   $SoftDeletedEmployees= Employee::onlyTrashed()->get();
   
   return response()->json(['soft_deleted_employee' => $SoftDeletedEmployees]);
}

}