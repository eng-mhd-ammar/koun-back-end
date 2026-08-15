<?php

namespace Modules\Address\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\Address\Models\State;
use Modules\Auth\Models\User;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::create(["name" => "دمشق"]);
        State::create(["name" => "ريف دمشق"]);
        State::create(["name" => "حلب"]);
        State::create(["name" => "حمص"]);
        State::create(["name" => "حماة"]);
        State::create(["name" => "اللاذقية"]);
        State::create(["name" => "طرطوس"]);
        State::create(["name" => "إدلب"]);
        State::create(["name" => "دير الزور"]);
        State::create(["name" => "الرقة"]);
        State::create(["name" => "الحسكة"]);
        State::create(["name" => "درعا"]);
        State::create(["name" => "السويداء"]);
        State::create(["name" => "القنيطرة"]);
    }
}
