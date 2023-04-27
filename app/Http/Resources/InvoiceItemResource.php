<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
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
            'quantity_unit' => $this->tagsForTypeSelect('quantity_unit'),
            'quantity_unit_name' => $this->tagsForTypeSelect('quantity_unit')['hu'][0],
            'trashed' => $this->trashed()
        ]);
    }
}
