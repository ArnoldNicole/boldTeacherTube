<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\Room;
//use App\Category;

class AdminController extends Controller
{

   public function __construct(){
  
      return $this->middleware('auth');
    }
  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { 
         $this->validate($request, [
            'noteName' => 'required|string|max:80',
            'noteBody'=>'required|string'            
        ]);
        $note=Note::create([
            'noteName'=>$request->noteName,
            'noteBody'=>$request->noteBody,
            'user_id'=>auth()->user()->id
        ]); 
         return $note;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showCategories()
    {
        return Category::orderBy('id','DESC')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return auth()->user()->notes;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function editNote(Request $request)
    {
        // validate request
        $this->validate($request, [
            'noteName' => 'required',
            'id' => 'required',
            'noteBody'=>'required'
        ]);
        return Note::where('id', $request->id)->update([
            'noteName' => $request->noteName,
            'noteBody'=> $request->noteBody,
        ]);
    }
    public function deleteNote(Request $request){
        if ($request->ajax()) {
           $this->validate($request, [
            'id' => 'required',
        ]);
         return Note::where('id', $request->id)->delete();
        }
    }
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file'=>'image|required|mimes:jpeg,jpg,png,svg,bmp|max:2048'
        ]);
        $imageName=time().'.'.$request->file->extension();
        $request->file->move(public_path('category_images'), $imageName);
        return $imageName;
    }
    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return "Deleted image" ;      
    }

    public function deleteFileFromServer($fileName){
        $filePath = public_path().'/category_images/'.$fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
            
        }
        return ; 
    }

    public function add_category(Request $request)
    {
    $this->validate($request, [
        'categoryName'=>['required','string'],
        'iconImage'=>['required','string']
    ]);
       $category=Category::create([
            'categoryName'=>$request->categoryName,
            'iconImage'=>$request->iconImage,
        ]);
         return $category;  
    }

    public function shareNote(Request $request){
        //dd($request->class);
        $note=Note::find($request->id);
        $classes = $request->class;
        foreach ($classes as $class) {
         //dd($class);
         $room = Room::find($class);
         $room->notes()->attach($note);
        }
        return ;

    }
}
