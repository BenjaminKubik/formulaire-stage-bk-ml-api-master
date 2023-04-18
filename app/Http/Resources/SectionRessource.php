<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionRessource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "titre" => $this->titre,
            "num_sec" => $this->num_sec,
            "form_id" => $this->form_id,
        ];
        //return parent::toArray($request);
    }
}
