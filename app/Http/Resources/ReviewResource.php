<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'username'=>$this->user->name,
            'review_response'=>$this->review_comment,
            'created_at'=>$this->created_at,
            'question_id'=>$this->review_question->id,
        ];
    }
}
