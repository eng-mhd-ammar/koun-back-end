<?php

namespace Modules\Auth\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\User;

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
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
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
    }
}
