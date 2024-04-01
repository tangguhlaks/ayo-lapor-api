<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report; // Assuming your Report model is located in 'app/Models/Report.php'

class ReportController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'type' => 'required|string',
            'prove' => 'required|max:5048',
            'description' => 'required|string',
            'status' => 'required|string',
            'mahasiswa' => 'required|integer',
            'dosen_wali' => 'required|integer',
        ]);
        if ($request->hasFile('prove')) {
            $image = $request->file('prove');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/assets/prove', $imageName);
            
            $report = new Report();
            $report->type = $request->type;
            $report->prove = $imageName;
            $report->description = $request->description;
            $report->status = $request->status;
            $report->mahasiswa = $request->mahasiswa;
            $report->dosen_wali = $request->dosen_wali;
            $report->save();
        } else {
            return response()->json(['status' => false, 'message' => 'Image is required'], 400);
        }
       

        // Redirect to a specified route or action
        return response()->json(['status' => true,'message'=>'Report Success Created'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {            
            // Find the report by its id
            $report = Report::findOrFail($id);

            // Return the view with the report data
            return response()->json(['status' => true,'data'=>$report], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$report], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        try {
            // Find the report by its id
            $report = Report::all();

            // Return the view with the report data
            return response()->json(['status' => true,'data'=>$report], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$report], 404);
        }
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
        // Find the report by its id
        $report = Report::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'type' => 'required|string',
            'prove' => 'sometimes|max:5048',
            'description' => 'required|string',
            'status' => 'required|string',
            'mahasiswa' => 'required|integer',
            'dosen_wali' => 'required|integer',
        ]);

        if ($request->hasFile('prove')) {
            $image = $request->file('prove');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/assets/prove', $imageName);
            // Delete previous image if exists
            Storage::delete('public/assets/prove/' . $report->prove);
            $report->prove = $imageName;
        }
        $report->type = $request->type;
        $report->description = $request->description;
        $report->status = $request->status;
        $report->mahasiswa = $request->mahasiswa;
        $report->dosen_wali = $request->dosen_wali;
        $report->save();
        
        return response()->json(['status' => true,'message'=>'Report Success Updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the report by its id
        $report = Report::findOrFail($id);
    
        // Delete the associated image file if it exists
        if ($report->prove) {
            Storage::delete('public/assets/prove/' . $report->prove);
        }
    
        // Delete the report
        $report->delete();
    
        // Return a JSON response indicating success
        return response()->json(['status' => true, 'message' => 'Report Successfully Deleted'], 200);
    }
    
}
