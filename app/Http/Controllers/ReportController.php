<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
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
            'title' => 'required|string',
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
            $image->move(public_path('assets/prove'), $imageName);

            $report = new Report();
            $report->title = $request->title;
            $report->type = $request->type;
            $report->prove = $imageName;
            $report->description = $request->description;
            $report->status = $request->status;
            $report->mahasiswa = $request->mahasiswa;
            $report->dosen_wali = $request->dosen_wali;
            $report->save();

            // Send FCM notification
            $firebase = (new Factory)->withServiceAccount(public_path('assets/credential_firebase.json'))->create();
            $messaging = $firebase->getMessaging();

            $message = CloudMessage::withTarget('topic', 'report_submitted')
                ->withNotification(Notification::create("Laporan Baru Dibuat", "Laporan dengan judul '$request->title' telah dibuat"))
                ->withData(['report_id' => $report->id]);

            try {
                $messaging->send($message);
            } catch (\Exception $e) {
                // Handle the error
                return response()->json(['status' => false, 'message' => 'Report created but notification failed: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Image is required'], 400);
        }

        // Redirect to a specified route or action
        return response()->json(['status' => true, 'message' => 'Report successfully created and notification sent'], 200);
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
            $report = Report::select("reports.*","users.first_name","users.last_name")
                              ->leftJoin("users","users.id","=","reports.mahasiswa")
                              ->get();

            // Return the view with the report data
            return response()->json(['status' => true,'data'=>$report], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$report], 404);
        }
    }

    public function showByUsername($username)
    {
        try {
            // Find the report by its id
            $report = Report::select("reports.*","users.first_name","users.last_name")
                              ->leftJoin("users","users.id","=","reports.mahasiswa")
                              ->where("users.username",$username)
                              ->get();

            // Return the view with the report data
            return response()->json(['status' => true,'data'=>$report], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$report], 404);
        }
    }


    public function showByStatus(Request $request)
    {
        try {
            $statuses = json_decode($request->input('status'), true);

            // Check if $statuses is an array, if not, make it an array
            if (!is_array($statuses)) {
                $statuses = [$statuses];
            }

            // Fetch reports with statuses in the array
            $report = Report::select("reports.*", "users.first_name", "users.last_name")
                ->leftJoin("users", "users.id", "=", "reports.mahasiswa")
                ->whereIn("reports.status", $statuses)
                ->get();
                
            // Return the view with the report data
            return response()->json(['status' => true,'data'=>$report], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$report], 404);
        }
    }

    public function getCountByStatus(Request $request)
    {
        try {
            // Find the report by its id
            $report = Report::select("reports.*","users.first_name","users.last_name")
            ->leftJoin("users","users.id","=","reports.mahasiswa")
            ->where("reports.status",$request->status)
            ->count();

            // Return the view with the report data
            return response()->json(['status' => true,'data'=>$report], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$report], 404);
        }
    }
    
    public function getCountByType(Request $request)
    {
        try {
            // Find the report by its id
            $report = Report::select("reports.*","users.first_name","users.last_name")
            ->leftJoin("users","users.id","=","reports.mahasiswa")
            ->where("reports.type",$request->type)
            ->count();

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
            'title' => 'required|string',
            'prove' => 'sometimes|max:5048',
            'description' => 'required|string',
            'status' => 'required|string',
            'mahasiswa' => 'required|integer',
            'dosen_wali' => 'required|integer',
        ]);

        if ($request->hasFile('prove')) {
            $image = $request->file('prove');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/prove'),$imageName);
            // Delete previous image if exists
            unlink(public_path('assets/prove/'.$news->prove));
            $report->prove = $imageName;
        }
        $report->title = $request->title;
        $report->type = $request->type;
        $report->description = $request->description;
        $report->status = $request->status;
        $report->mahasiswa = $request->mahasiswa;
        $report->dosen_wali = $request->dosen_wali;
        $report->save();
        
        return response()->json(['status' => true,'message'=>'Report Success Updated'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        // Find the report by its id
        $report = Report::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'status' => 'required|string'
        ]);
        $report->status = $request->status;
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
            unlink(public_path('assets/prove/'.$report->prove));
        }
    
        // Delete the report
        $report->delete();
    
        // Return a JSON response indicating success
        return response()->json(['status' => true, 'message' => 'Report Successfully Deleted'], 200);
    }
    
}
