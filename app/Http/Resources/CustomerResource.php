<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'sex' => $this->sex,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
            'nationality' => $this->nationality,
            'country_of_origin' => $this->country_of_origin,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'phone' => $this->phone,
            'email' => $this->email,
            'created_at_branch' => $this->createdAtBranch,
            'created_by' => $this->createdBy,
            'lastly_updated_by' => $this->lastlyUpdatedBy,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
