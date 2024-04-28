<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $projects = Project::with('employees')->get();
        return $this->customeResponse($projects,"Done",200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        try {
            DB::beginTransaction();
            $project = Project::create([
                'project_name' => $request->project_name,
                'description' => $request->description,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
            ]
            );

            if ($request->has('employees_id')) {
                $project->employees()->attach($request->employees_id);
            }

            DB::commit();

            return $this->customeResponse($project,"Done",200);
        }  catch   (\Exception $e) {
            Log::error($th);
            DB::rollBack();
            return $this->customeResponse(null, 'Failed To Create', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $project->project_name= $request->input('naproject_name') ?? $project->project_name;
            if ($request->has('employees_id')) {
                $project->employees()->sync($request->employees_id);
            }

            DB::commit();
            $project->save();
        
            return $this->customeResponse($data, 'Successfully Updated', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return response()->json(['message' => 'Error !'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project->delete();
        return $this->customeResponse('', 'Deleted', 200);
    }
}
