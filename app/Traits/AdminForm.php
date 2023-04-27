<?php

namespace App\Traits;

use App\Models\Admin;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

trait AdminForm
{

	/**
	 * @param bool $confirmed
	 *
	 * @return string[]
	 */
	public function passwordRules($confirmed = false)
	{
		$rules = [
			'required',
			'string',
			Password::defaults() // Found in AppServiceProvider
		];

		if ($confirmed) {
			$rules[] = 'confirmed';
		}

		return $rules;
	}

	/**
	 * @param Admin|null $admin
	 * @param           $editByAdmin
	 * @return array|\string[][]
	 */
	public function adminFormRules(?Admin $admin = null, $editByAdmin = false)
	{
		$rules = [
			'name' =>  ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin ? $admin->id : null)],
			'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
		];

		// Admin edit and not invite
		if($admin && $editByAdmin) {
			$edit = [
				'status' => ['required', Rule::in(array_keys(Admin::STATUSES))],
				'roles' => ['nullable', 'array'],
				'permissions' => ['nullable', 'array'],
			];
		}

		return array_merge($rules, $edit ?? []);
	}

	/**
	 * @return array
	 */
	public function registerRules(): array
	{
		return array_merge(
			$this->adminFormRules(),
			['password' => $this->passwordRules(true)]
		);
	}
}
