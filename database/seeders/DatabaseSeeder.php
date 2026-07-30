<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrator']);
        $studentRole = Role::firstOrCreate(['name' => 'Student']);
        $examinerRole = Role::firstOrCreate(['name' => 'Examiner']);
        $supervisorRole = Role::firstOrCreate(['name' => 'Supervisor']);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@acetel.edu.ng'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        // Create a test examiner
        $examiner = User::firstOrCreate(
            ['email' => 'examiner@acetel.edu.ng'],
            [
                'name' => 'Dr. Test Examiner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $examiner->assignRole($examinerRole);

        // Create a test student
        $studentUser = User::firstOrCreate(
            ['email' => 'student@acetel.edu.ng'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $studentUser->assignRole($studentRole);

        $this->call([
            DepartmentSeeder::class,
            ProgrammeSeeder::class,
            SystemSettingSeeder::class,
        ]);

        // Create test student record
        $testStudent = \App\Models\Student::firstOrCreate(
            ['matric_number' => 'NOU123456789'],
            [
                'user_id' => $studentUser->id,
                'phone_number' => '08012345678',
                'department_id' => \App\Models\Department::where('name', 'Cybersecurity')->first()->id,
                'programme_id' => \App\Models\Programme::where('name', 'MSc')->first()->id,
                'academic_session' => '2025/2026',
                'year_of_admission' => '2024',
                'intake' => 1,
                'research_title' => 'An Analysis of Cybersecurity Threats in E-learning Platforms',
                'current_research_stage' => 'Data Collection',
            ]
        );

        // Create a test supervisor
        $testSupervisor = User::firstOrCreate(
            ['email' => 'supervisor@acetel.edu.ng'],
            [
                'name' => 'Prof. Jane Smith',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $testSupervisor->assignRole($supervisorRole);
        $testStudent->supervisors()->syncWithoutDetaching([$testSupervisor->id => ['status' => 'approved']]);

        // Create 15 Dummy Students for testing (only in local environment where Faker is installed)
        if (app()->environment('local') && class_exists(\Faker\Factory::class)) {
            $departments = \App\Models\Department::all();
            $programmes = \App\Models\Programme::all();
            $faker = \Faker\Factory::create();

        if ($departments->count() > 0 && $programmes->count() > 0) {
            // Pre-create some dummy supervisors
            $dummySupervisors = [];
            for ($s = 1; $s <= 5; $s++) {
                $sup = User::firstOrCreate(
                    ['email' => "supervisor{$s}@acetel.edu.ng"],
                    [
                        'name' => 'Dr. ' . $faker->lastName,
                        'password' => Hash::make('password'),
                        'email_verified_at' => now(),
                    ]
                );
                $sup->assignRole($supervisorRole);
                $dummySupervisors[] = $sup;
            }

            for ($i = 1; $i <= 15; $i++) {
                $dummyUser = User::firstOrCreate(
                    ['email' => "student{$i}@acetel.edu.ng"],
                    [
                        'name' => $faker->name,
                        'password' => Hash::make('password'),
                        'email_verified_at' => now(),
                    ]
                );
                $dummyUser->assignRole($studentRole);

                $prog = $programmes->random();
                $dummyStudent = \App\Models\Student::firstOrCreate(
                    ['matric_number' => 'NOU' . $faker->unique()->numerify('#########')],
                    [
                        'user_id' => $dummyUser->id,
                        'phone_number' => $faker->phoneNumber,
                        'department_id' => $departments->random()->id,
                        'programme_id' => $prog->id,
                        'academic_session' => '2025/2026',
                        'year_of_admission' => '2024',
                        'intake' => $faker->randomElement([1, 2]),
                        'research_title' => $faker->sentence(6),
                        'current_research_stage' => $faker->randomElement(['Literature Review', 'Data Collection', 'Data Analysis', 'Writing']),
                    ]
                );
                
                // Assign supervisors
                $isPhd = str_contains(strtolower($prog->name), 'phd') || str_contains(strtolower($prog->name), 'doctor');
                $supCount = $isPhd ? 3 : 2;
                
                $assignedSups = collect($dummySupervisors)->random($supCount);
                foreach ($assignedSups as $sup) {
                    $dummyStudent->supervisors()->syncWithoutDetaching([
                        $sup->id => ['status' => $faker->randomElement(['pending', 'approved'])]
                    ]);
                }
            }
        }
        }
    }
}
