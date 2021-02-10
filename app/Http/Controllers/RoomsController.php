<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;

class RoomsController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return only undeleted classes from the said user
        return auth()->user()->rooms;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data =  $this->validate($request, [
                    'className'=>'required|unique:rooms|string',
                    'classCode'=>'required|unique:rooms|string|alpha_num',
                    'studentsCount'=>'required|numeric|min:5',
                    'status'=>'required|string',
                ]);
         return auth()->user()->rooms()->create($data);
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
    public function update(Request $request)
    {
        $data =  $this->validate($request, [
                    'className'=>'required|string',
                    'classCode'=>'required|string|alpha_num',
                    'studentsCount'=>'required|numeric|min:5',
                    'status'=>'required|string',
            ]);
        $room =Room::find($request->id)->first();
        $room->update([
            'className'=>$data['className'],
            'classCode'=>$data['classCode'],
            'studentsCount'=>$data['studentsCount'],
            'status'=>$data['status'],
         ]);
        return $room;
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


    public function available(){
       return $rooms = Room::where('status','Active')->get();
    }

    public function join(Room $room){
        return auth()->user()->attending()->toggle($room);
    }

    
}
