<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
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
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'account_balance' => $this->account_balance,
            'owner' => $this->owner,
            'created_at_branch' => $this->createdAtBranch,
            'created_by' => $this->createdBy,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
