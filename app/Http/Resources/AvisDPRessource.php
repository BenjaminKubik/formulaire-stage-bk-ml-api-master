<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvisDPResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "id" => $this->id,
            "nom" => $this->nom,
            "commentaire" => $this->commentaire,
            "note" => $this->note,
            "user_id" => $this->user
        ];
//        return parent::toArray($request);
    }
}
