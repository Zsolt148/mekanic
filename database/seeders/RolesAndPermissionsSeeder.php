<?php

namespace Database\Seeders;

use App\Helpers\AdminFeatures;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// spatie laravel permissions
		// https://spatie.be/docs/laravel-permission/v5/introduction

		// Reset cached roles and permissions
		app()[PermissionRegistrar::class]->forgetCachedPermissions();
		cache()->clear();

		// create permissions
		// permission policies as default laravel policy - policy name (controller method)
		// viewAny (index), view (show), create (create,store), update (edit,update), delete (destroy)

		Role::findOrCreate('superadmin');
		Role::findOrCreate('admin');
		Role::findOrCreate('owner');

		//$role->syncPermissions(Permission::all());
	}
}
