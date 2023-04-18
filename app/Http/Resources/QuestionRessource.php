<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionRessource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "id" => $this->id,
            "num_question" => $this->num_question,
            "libelle" => $this->libelle,
            "type" => $this->type,
            "num_sec" => $this->num_sec,
            "form_id" => $this->form_id,
            "min" => $this->min,
            "max" => $this->max,
            "pas" => $this->pas,
        ];
        //return parent::toArray($request);
    }
}
