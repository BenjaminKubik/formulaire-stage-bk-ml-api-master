<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MultipleAnswerRessource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "user_id" => $this->user_id,
            "question_id" => $this->question_id,
            "answer" => $this->answer,
        ];
        //return parent::toArray($request);
    }
}
