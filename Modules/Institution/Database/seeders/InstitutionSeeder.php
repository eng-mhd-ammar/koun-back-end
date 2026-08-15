<?php

namespace Modules\Institution\Database\seeders;

use Modules\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Address\Models\State;
use Modules\Institution\Models\Branch;
use Modules\Institution\Models\Institution;
use Modules\Address\Models\Address;
use Modules\Institution\Enums\InstitutionType;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $users = User::query()->get();
        $states = State::query()->pluck('id')->toArray();

        if ($users->isEmpty()) {
            $this->command->error('No users found.');
            return;
        }

        if (empty($states)) {
            $this->command->error('No states found.');
            return;
        }

        DB::transaction(function () use ($faker, $users, $states) {

            for ($i = 1; $i <= 50; $i++) {

                /*
                |--------------------------------------------------------------------------
                | Institution
                |--------------------------------------------------------------------------
                */

                $owner = $users->random();

                $institution = Institution::create([
                    'logo' => null,
                    'name' => $faker->company(),
                    'description' => $faker->optional()->sentence(),
                    'owner_id' => $owner->id,
                    'phone' => '09' . $faker->numerify('########'),
                    'email' => $faker->unique()->safeEmail(),
                    'type' => $faker->randomElement([
                        InstitutionType::DONOR->value,
                        InstitutionType::CHARITY->value,
                        InstitutionType::BOTH->value,
                    ]),
                    'is_active' => true,
                    'attachments' => [],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Institution Employees
                |--------------------------------------------------------------------------
                */

                $institutionEmployees = $users
                    ->random($faker->numberBetween(3, 5));

                foreach ($institutionEmployees as $employee) {

                    DB::table('user_institutions')->insert([
                        'institution_id' => $institution->id,
                        'user_id' => $employee->id,
                        'is_admin' => $employee->id === $owner->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Branches
                |--------------------------------------------------------------------------
                */

                $branchesCount = $faker->numberBetween(3, 10);

                for ($j = 1; $j <= $branchesCount; $j++) {

                    $branch = Branch::create([
                        'name' => "فرع {$j} - {$institution->name}",
                        'description' => $faker->optional()->sentence(),
                        'institution_id' => $institution->id,
                        'phone' => '09' . $faker->numerify('########'),
                        'email' => $faker->unique()->safeEmail(),
                        'is_main_branch' => $j === 1,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Branch Address - ONE address per branch
                    |--------------------------------------------------------------------------
                    */

                    Address::create([
                        'branch_id' => $branch->id,
                        'state_id' => $faker->randomElement($states),
                        'city' => $faker->city(),
                        'street' => $faker->streetName(),
                        'latitude' => $faker->latitude(32, 37),
                        'longitude' => $faker->longitude(35, 43),
                        'details' => $faker->optional()->sentence(),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Branch Employees
                    |--------------------------------------------------------------------------
                    */

                    $branchEmployees = $users
                        ->random($faker->numberBetween(3, 5));

                    foreach ($branchEmployees as $employee) {

                        DB::table('user_branches')->insert([
                            'branch_id' => $branch->id,
                            'user_id' => $employee->id,
                            'is_admin' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });
    }
}
