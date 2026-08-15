<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\seeders\AdminSeeder;
use Modules\Donation\Database\seeders\DonationTypeSeeder;
use Modules\Auth\Database\seeders\RoleSeeder;
use Modules\Address\Database\seeders\StateSeeder;
use Modules\Donation\Database\seeders\DonationSeeder;
use Modules\Donation\Database\seeders\UnitSeeder;
use Modules\Institution\Database\seeders\InstitutionSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,

            StateSeeder::class,
            UnitSeeder::class,
            DonationTypeSeeder::class,

            InstitutionSeeder::class,
            DonationSeeder::class,
        ]);
    }
}
