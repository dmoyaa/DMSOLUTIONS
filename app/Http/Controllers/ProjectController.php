<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController
{
    public function index()
    {
        $projects = DB::table('project_status')->get();
        return view('Browse', ['projects' => $projects]);
    }

    public function consult(Request $request)
    {
        try {
            $projectConsult = DB::table('projects')->where('quote_id', $request->project)->first();
            if ($projectConsult) {
                return response()->json([
                    'success' => 'true',
                    'data' => $projectConsult
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron productos'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function consultDetail(Request $request)
    {
        try {
            $projectConsult = DB::table('projects')->where('id', $request->projectDetail)->first();
            if ($projectConsult) {
                return response()->json([
                    'success' => 'true',
                    'data' => $projectConsult
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron proyectos'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function store(Request $request, Project $project){
        $project -> id = 0;
        $project -> quote_id = $request->hiddenQuoteId;
        $project -> proj_name= $request->projName;
        $project -> proj_start_date = $request->projStartDate;
        $project -> proj_end_date = $request->projEndDate;
        $project -> proj_visit = $request->calendar;
        $project -> proj_deposit = $request->projDeposit;
        $project -> proj_warranty = $request->projWarranty;
        $project -> status_id = 1;
        $project -> save();
        try{
            $project->save();
            return to_route('browse')->with('status','Proyecto Agregado exitosamente');
        }catch (Exception $e){
            return to_route('quote')->with('status','Error al agregar el proyecto'.$e->getMessage());
        }
    }

    public function update(Request $request,Project $project)
    {
        $project = Project::find($request->hiddenProjectId);
        $quote_id = $project -> quote_id;
        $project -> quote_id = $quote_id;
        $project -> proj_name= $request->projName;
        $project -> proj_start_date = $request->projStartDate;
        $project -> proj_end_date = $request->projEndDate;
        $project -> proj_visit = $request->datetimeInput;
        $project -> proj_deposit = $request->projDeposit;
        $project -> proj_warranty = $request->projWarranty;
        $project -> status_id = $request -> menuStatus;
        try {
            $project->save();
            return redirect()->back();
        }catch (Exception $e){
            return redirect()->back()->with('status',$e->getMessage());
        }
    }

    public function destroy(Request $request){
        $project = Project::find($request->id);
        try{
            $project->delete();
            return to_route('browse');
        }catch (Exception $e){
            return to_route('browse')->with('status','Error al eliminar el proyecto'.$e->getMessage());
        }
    }
}
