<?php

namespace App\Http\Resources;

use App\Models\Contact;
use App\Models\Car;
use App\Http\Resources\PartnerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'trashed' => $this->trashed(),
        ]);
    }
}
