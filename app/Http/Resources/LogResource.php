<?php

namespace App\Http\Resources;

use App\Models\Activity;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
		/** @var Activity $this */
        return [
            "description" => __($this->description),
            "name" => $this->getName($this->resource),
            "causer" => $this->causer ? $this->causer->name : '',
            "event" => trans($this->event),
            "props" => $this->properties,
            'old_values' => isset($this->properties['old']) ? $this->properties['old'] : '',
            'new_values' => isset($this->properties['attributes']) ? $this->properties['attributes'] : '',
            'properties' => $this->properties,
            'created_at' => $this->created_at
        ];
    }

    /**
     * @param LogResource $log
     * @return mixed|string
     */
    private function getName(Activity $log)
    {
		// App\Models\Settings -> Settings
		$name = trans(last(explode('\\', $log->subject_type)));

        // subcjet is not deleted
        if($log->subject && isset($log->subject->name)) {
			$name .= ' - ' . $log->subject->name;
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
