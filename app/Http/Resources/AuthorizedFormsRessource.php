<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorizedFormsRessource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            "user_id" => $this->user_id,
            "form_id" => $this->form_id,
        ];
        //return parent::toArray($request);
    }
}
