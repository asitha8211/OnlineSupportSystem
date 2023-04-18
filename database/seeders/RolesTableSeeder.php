<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        $roles = [
            ['name' => 'support_agent', 'display_name' => 'Support Agent'],
            ['name' => 'customer', 'display_name' => 'Customer'],
            ['name' => 'seller', 'display_name' => 'Seller'],
            ['name' => 'service_provider', 'display_name' => 'Service Provider'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
