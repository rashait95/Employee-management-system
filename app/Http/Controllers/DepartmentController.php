<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Requests\updateDepartmentRequest;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $departments =Department::with('employees')->get();
                return response()->json([
                    'status'=>'success',
                    'departments'=>$departments
                ]);
    }
        
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        
        try{
            DB::beginTransaction();

            $departments= Department::create([
                'name'=>$request->name,
                'description'=>$request->description,
            ]);


            DB::commit();

           return response()->json(
            ['status'=>'department created successfuly',
            'departments'=> $departments,]
            );



        }
        catch(\Throwable  $th){

                DB::rollback();
                Log::error($th);
                return response()->json([
                    'status'=>'department not created',
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
    public function show(Department $department)
    {
     
        return response()->json([
            'status'=>'success',
            'department'=>$department,
          ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(updateDepartmentRequest $request, Department $department)
    {
        
     dd($request);
        
     $department->update([
        'name'=>$request->name,
        'description'=>$request->description,
     ]);
     $department->save();

     return response()->json([
                'status'=>'department updated succesfully',
                'departments'=>$department
            ]);
            // DB::beginTransaction();

            // if($request->has('name')){
            //     $department->update([
            //         'name'=>$request->name]);
            // }

            // elseif ($request->has('description')) {
            //     $department->update([
            //         'description'=>$request->description
            //     ]);
        
            // }


        

        //         $department->update([
        //             'name'=>$request->name,
        //             'description'=>$request->description
           
           
        //         ]);
         

        //     return response()->json([
        //         'status'=>'department updated succesfully',
        //         'department'=>$department
        //     ]);
           

        // }catch(\Throwable $th){
           
        //         Log::error($th);
        //         return response()->json([
        //             'status'=>'department not updated',
        //             'error'=>$th->getMessage(),
                    
        //         ], 500);


        // }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json([
         'status'=>'department deleted succesfully',
         'departments'=>$department
        ]);
     }
    
     public function restore(Department $department)
     {
         $department->restore();
 
         return response()->json(['department' => $department], 400);
     }
 
     public function forceDelete(Department $department)
     {
         $$department->forceDelete();
 
         return response()->json(null, 400);
     }

     public function showSoftDeletedDepartments()
{
    $SoftDeletedDepartments = Department::onlyTrashed()->get();
    
    return response()->json(['soft_deleted_departments' => $SoftDeletedDepartments]);
}
 
  
}
