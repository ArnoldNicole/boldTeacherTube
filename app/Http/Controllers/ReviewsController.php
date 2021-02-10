<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ReviewQuestionResource;
use App\Http\Resources\ReviewResource;
use App\ReviewQuestion;

class ReviewsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReviewQuestionResource::collection(auth()->user()->review_questions);
    }

    /**
     * Save a review to the database and broadcast it.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       $data = $this->validate($request, [
        'id'=>'required|numeric',
        'myresponse'=>'required|string|min:5|max:500',
       ]);
       $res = auth()->user()->reviews()->create([
        'review_comment'=>$data['myresponse'],
        'review_question_id'=>$data['id'],
       ]);
       return new ReviewResource($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'review_body'=>'string|required|min:10|max:1000|unique:review_questions'
        ]);
        return auth()->user()->review_questions()->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return ReviewQuestionResource::collection(ReviewQuestion::all());
    }


    /**
     * Get all review responses for submitted selected question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function all_reviews(Request $request)
    {
        $data = $this->validate($request, [
            'id'=>'required|numeric'
        ]);
        $review_question = ReviewQuestion::find($data['id']);
        return ReviewResource::collection($review_question->reviews);
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
