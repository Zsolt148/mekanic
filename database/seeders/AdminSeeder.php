<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{

    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::updateOrCreate([
                'email' => 'mekanic@mekanic.com',
            ], [
                'name' => 'Mekanic admin',
                'email_verified_at' => now(),
                'password' => Hash::make('mekanic'),
            ]);

        $admin->assignRole('superadmin');
    }
}
