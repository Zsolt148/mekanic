<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
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
        $service = Service::updateOrCreate([
                'created_by' => '1',
            ], [
                'name' => 'Példa szolgáltatás'
            ]);
    }
}
