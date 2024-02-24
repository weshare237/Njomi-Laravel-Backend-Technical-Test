<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistoryResource extends JsonResource
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
            'bank_account' => $this->bankAccount,
            'transaction_type' => $this->transaction_type,
            'transaction_action' => $this->transaction_action,
            'reference' => $this->reference,
            'amount' => $this->amount,
            'charges' => $this->charges,
            'total_transaction_amount' => $this->total_transaction_amount,
            'account_balance_after_transaction' => $this->account_balance_after_transaction,
            'targeted_external_bank_account_id' => $this->targetedExternalBankAccount,
            'comments' => $this->comments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
