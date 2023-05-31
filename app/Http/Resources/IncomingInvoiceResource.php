<?php

namespace App\Http\Resources;

use App\Models\Contact;
use App\Models\IncomingInvoice;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomingInvoiceResource extends JsonResource
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
            'payment_mode_name' => __(IncomingInvoice::PAYMENT_MODES[$this->payment_mode]),
            'issuer' => $this->tagsForTypeSelect('issuer'),
            'issuer_name' => $this->tagsForTypeSelect('issuer'),
            'type' => $this->tagsForTypeSelect('type'),
            'type_name' => $this->tagsForTypeSelect('type'),
            'gross_text' => getCurrencyFormat($this->gross),
            'net_text' => getCurrencyFormat($this->net),
            'trashed' => $this->trashed()
        ]);
    }
}
