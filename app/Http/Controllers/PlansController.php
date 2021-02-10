<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;

class PlansController extends Controller
{
	public function __construct(){
		return $this->middleware(['auth','lecturer']);
	}
    public function create(Request $request){
    	 $this->validate($request, [
    	    'planName' => 'required|string|max:30',
    	    'planInfo'=>'required|string'            
    	]);
    	$plan=Plan::create([
    	    'planName'=>$request->planName,
    	    'planInfo'=>$request->planInfo,
    	    'user_id'=>auth()->user()->id
    	]); 
    	 return $plan;
    }
	public function edit(){
		// validate request
		$this->validate($request, [
		    'planName' => 'required',
		    'id' => 'required',
		    'planInfo'=>'required'
		]);
		return Plan::where('id', $request->id)->update([
		    'planName' => $request->planName,
		    'planInfo'=> $request->planInfo,
		]);
	}
	public function destroy(Request $request){
		if ($request->ajax()) {
		   $this->validate($request, [
		    'id' => 'required',
		]);
		 return Plan::where('id', $request->id)->delete();
		}
	}
  public function index(){
 	 return auth()->user()->plans; 	
  }  
}
