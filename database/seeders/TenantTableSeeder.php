<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TenantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Vitor',
            'email' => 'v@v',
            'password' => bcrypt('11111111'),
            'role_as'  => 2,
            'status'   => 1
        ]);
    }
}
