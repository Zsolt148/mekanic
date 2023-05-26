<?php

namespace App\Http\Resources;

use App\Models\Worksheet;
use Illuminate\Http\Resources\Json\JsonResource;
/** @mixin Worksheet */
class WorksheetResource extends JsonResource
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
            'admin' => AdminResource::make($this->whenLoaded('admin')),
            'partner' => PartnerResource::make($this->whenLoaded('partner')),
            'car' => CarResource::make($this->whenLoaded('car')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'service_names' => $this->whenLoaded('services', fn() => $this->services->pluck('name')->join(', ')),
            'type_text' => Worksheet::TYPES[$this->resource->type],
            'created_at_diff' => $this->resource->created_at->diffForHumans()
        ]);
    }
}
