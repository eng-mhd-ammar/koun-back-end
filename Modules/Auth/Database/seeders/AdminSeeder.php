<?php

namespace Modules\Auth\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\User;
use Faker\Factory as Faker;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'avatar' => null,
            'first_name' => 'admin',
            'last_name' => 'admin',
            'username' => 'admin',
            'password' => 'admin123',
            'email' => 'admin@example.com',
            'phone' => '0999999999',
            'birthday' => now(),
            'gender' => 1,
        ]);

        $user->verificationCodes()->create([
            'type' => 1,
            'target' => $user->email,
            'code' => '123456',
            'expired_at' => now()->addMinutes(10),
            'verified_at' => now(),
        ]);

        $user->verificationCodes()->create([
            'type' => 2,
            'target' => $user->phone,
            'code' => '123456',
            'expired_at' => now()->addMinutes(10),
            'verified_at' => now(),
        ]);

        $user->assignRole(['admin', 'user', 'delivery']);

        $faker = Faker::create();

        $roles = [
            'user',
            'admin',
            'delivery',
        ];

        for ($i = 0; $i < 100; $i++) {

            $user = User::create([
                'avatar' => null,
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'username' => $faker->unique()->userName(),
                'password' => 'password',
                'email' => $faker->unique()->safeEmail(),
                'phone' => '+963' . $faker->unique()->numerify('########'),
                'birthday' => $faker->dateTimeBetween('-50 years', '-18 years'),
                'gender' => $faker->randomElement([0, 1]),
            ]);

            // Email verification
            $user->verificationCodes()->create([
                'type' => 1,
                'target' => $user->email,
                'code' => '123456',
                'expired_at' => now()->addMinutes(10),
                'verified_at' => now(),
            ]);

            // Phone verification
            $user->verificationCodes()->create([
                'type' => 2,
                'target' => $user->phone,
                'code' => '123456',
                'expired_at' => now()->addMinutes(10),
                'verified_at' => now(),
            ]);

            // Random roles
            $userRoles = $faker->randomElements(
                $roles,
                $faker->numberBetween(1, 3)
            );

            $user->assignRole($userRoles);
        }
    }
}
