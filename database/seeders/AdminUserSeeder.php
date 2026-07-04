<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the Administrator role exists
        $role = Role::firstOrCreate(['name' => 'Administrator']);

        // Create or update the specific admin user
        $user = User::updateOrCreate(
            ['email' => 'isinan@noun.edu.ng'],
            [
                'name' => 'Dr. Sinan',
                'password' => Hash::make('Sinan3367#'),
                'email_verified_at' => now(),
            ]
        );
        
        // Assign the role
        $user->assignRole($role);
    }
}
