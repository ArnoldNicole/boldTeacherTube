<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;

class ReadController extends Controller
{
    public function notes_from_class_loader(Request $request){
    	$room = Room::find($request->id);
    	return $room->notes;
    }

    public function my_classes(){
    	//$classes = auth()->user()->attending;
    	//dd($classes);
    	return auth()->user()->attending;
    }
}
