<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormRessource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "id" => $this->id,
            "titre" => $this->titre,
            "nbSec" => $this->nbSec,
            "prive" => $this->prive,
        ];
        //return parent::toArray($request);
    }
}
