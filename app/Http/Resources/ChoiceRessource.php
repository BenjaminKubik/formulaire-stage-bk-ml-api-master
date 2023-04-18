<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChoiceRessource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "choice" => $this->choice,
            "num_question" => $this->num_question,
            "num_sec" => $this->num_sec,
            "form_id" => $this->form_id,
        ];
        //return parent::toArray($request);
    }
}
