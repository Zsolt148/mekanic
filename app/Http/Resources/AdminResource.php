<?php

namespace App\Http\Resources;

use App\Models\Admin;
use App\Models\PlannedTask;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Admin $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo'  => $this->profile_photo_url,
            'profile_photo_path' => $this->profile_photo_path,
            'status' => $this->status,
            'statusText' => $this->getStatusText(),
            'statusDate' => $this->getStatusDate(),

            'roles' => $this->whenLoaded('roles'),
            'roleIds' => $this->whenLoaded('roles', function () {
				return $this->roles->pluck('id')->toArray();
			}, []),
            'rolesText' => $this->whenLoaded('roles', function () {
				return $this->roles->pluck('name')->map(fn ($name) => __('role.'.$name))->join(', ');
			}, []),

			'permissions' => $this->whenLoaded('permissions'),
			'permissionIds' => $this->whenLoaded('permissions', function () {
				return $this->permissions->pluck('id')->toArray();
			}, []),
			'permissionText' => $this->whenLoaded('permissions', function () {
				return $this->permissions->pluck('name')->map(fn ($name) => __('permission.'.$name))->join(', ');
			}, []),

            'trashed' => $this->trashed(),
            'is_registered' => $this->isRegistered(),
            'is_invited' => $this->isInvited(),
            'is_blocked' => $this->isBlocked(),

            'last_login_at' => $this->last_login_at ? $this->last_login_at->format('Y-m-d') : '-',
            'blocked_at' => $this->blocked_at,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

			'class' => Admin::class,
        ];
    }
}
