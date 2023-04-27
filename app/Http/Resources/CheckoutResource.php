<?php

namespace App\Http\Resources;

use App\Models\Checkout;
use App\Models\Contact;
use App\Models\IncomingInvoice;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'type_name' => __(Checkout::TYPE[$this->type]),
            'title' => $this->tagsForTypeSelect('title'),
            'title_name' => $this->tagsForTypeSelect('title')['hu'][0],
            'amount_text' => getCurrencyFormat($this->amount),
            'trashed' => $this->trashed()
        ]);
    }
}
