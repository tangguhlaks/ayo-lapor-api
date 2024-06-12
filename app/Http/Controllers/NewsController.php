<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News; // Assuming your News model is located in 'app/Models/News.php'

class NewsController extends Controller
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
            'image' => 'required|max:5048',
            'description' => 'required|string'
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/news'),$imageName);
            $news = new News();
            $news->title = $request->title;
            $news->image = $imageName;
            $news->description = $request->description;
            $news->save();
        } else {
            return response()->json(['status' => false, 'message' => 'Image is required'], 400);
        }
       
        // Redirect to a specified route or action
        return response()->json(['status' => true,'message'=>'News Success Created'], 200);
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
            // Find the News by its id
            $news = News::findOrFail($id);

            // Return the view with the News data
            return response()->json(['status' => true,'data'=>$news], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$news], 404);
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
            // Find the News by its id
            $news = News::select("news.*")->orderBy("news.id","DESC")->get();

            // Return the view with the News data
            return response()->json(['status' => true,'data'=>$news], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'data'=>$news], 404);
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
        // Find the News by its id
        $news = News::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'image' => 'sometimes|max:5048',
            'description' => 'required|string'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/news'),$imageName);
            // Delete previous image if exists
            unlink(public_path('assets/news/'.$news->image));
            $news->image = $imageName;
        }
        $news->title = $request->title;
        $news->description = $request->description;
        $news->save();
        
        return response()->json(['status' => true,'message'=>'News Success Updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the News by its id
        $news = News::findOrFail($id);
    
        // Delete the associated image file if it exists
        if ($news->image) {
            unlink(public_path('assets/news/'.$news->image));
        }
    
        // Delete the News
        $news->delete();
    
        // Return a JSON response indicating success
        return response()->json(['status' => true, 'message' => 'News Successfully Deleted'], 200);
    }
    
}
