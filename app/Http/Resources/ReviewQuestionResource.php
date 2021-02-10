<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewQuestionResource extends JsonResource
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
            'review_body'=>$this->review_body,
            'answers_count'=>$this->reviews->count(),
            'created_at'=>$this->created_at,
            'owner'=>$this->user->name,
            'id'=>$this->id,
        ];
    }
}
