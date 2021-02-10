<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use Auth;
use App\Events\NewMessage;

class ChatController extends Controller
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
        return  User::where('id', '!=', auth()->id())->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     $data=request()->validate([           
           'message'=>['required','string'],
           'user_id'=>['required'],
           ]);
             //dd($data);
               if (User::find($data['user_id'])) {               
              $message =  auth()->user()->messages()->create([
                'from_user'=>auth()->user()->id,
                'to_user'=>$data['user_id'],
                'content'=>$data['message']
              ]);
              //dd($message->id);
             broadcast(new NewMessage($message))->toOthers();
               

            return $message->toJson();
               }else{
                 abort(404,'The user you are trying to chat with doesn`t exist');
               }//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
     if ($user->id==auth()->user()->id) {
        abort(404,'You cant Chat alone');
    }
    if ($user) {            
         $messages = Message::where(function($query) use ($user) {
                            $query->where('from_user', Auth::user()->id)->where('to_user', $user->id);
                        })->orWhere(function ($query) use ($user) {
                            $query->where('from_user', $user->id)->where('to_user', Auth::user()->id);
                        })->orderBy('created_at', 'ASC')->get();
         foreach ($messages as $message ) {
         $message=[
                'content'=>$message->content,
                'from'=>$message->from_user,
                'to'=>$message->to_user,
                'created_at'=>$message->created_at->diffForHumans(),
                ];        
         }
        return response()->json($messages); 
        }
        else{
             abort(404, 'Uknown User');
        }
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
}
