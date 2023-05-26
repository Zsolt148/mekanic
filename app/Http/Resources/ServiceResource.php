<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            "description" => __($this->description),
            "name" => $this->getName($this->resource),
            'created_at' => $this->created_at,
            'comment' => $this->comment,
        ];
        /* return array_merge(parent::toArray($request), [
            'name' => $this->tagsForTypeSelect('name')['hu'][0],
            "description" => __($this->description),
        ]); */
    }

    private function getName(Activity $service)
    {
		// App\Models\Settings -> Settings
		$name = trans(last(explode('\\', $service->subject_type)));

        // subcjet is not deleted
        if($service->subject && isset($service->subject->name)) {
			$name .= ' - ' . $service->subject->name;
        }
        // subject is restored
        elseif(isset($this->properties['attributes'])) {
            $attributes = $this->properties['attributes'];

            if(isset($attributes['name'])) {
                $name .= $attributes['name'];
            }
        }
        // subject is deleted
        elseif(isset($this->properties['old'])) {
            $attributes = $this->properties['old'];

            if(isset($attributes['name'])) {
                $name .= $attributes['name'];
            }
        }

        return $name;
    }
}
