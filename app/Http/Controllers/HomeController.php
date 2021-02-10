<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       //
        //if user has no is_teacher redirect to /create_profile
        if (auth()->user()->account_type == null) {
           return redirect('/complete_profile');
           //return view('home');
        }else{
             return view('home');
        }
    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            'account_type'=>'string|required',
            'date_of_birth'=>'date|required',
            'gender' => 'string|required'
        ]);
        //Profile::findornew(auth()->user()->id);
        if (auth()->user()->profile == null) {
            auth()->user()->profile()->create([
            'gender'=>$request->gender,
            'date_of_birth'=>$request->date_of_birth
            ]);
        }else{
          auth()->user()->profile()->update([
              'gender'=>$request->gender,
              'date_of_birth'=>$request->date_of_birth
          ]);  
        }
        auth()->user()->update([
            'account_type'=>$request->account_type
        ]);
        
        return "ok";
    }
}
