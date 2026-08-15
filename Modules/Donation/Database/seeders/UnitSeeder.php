<?php

namespace Modules\Donation\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\Address\Models\State;
use Modules\Auth\Models\User;
use Modules\Donation\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create(["name" => "KG", "description" => "Kilogram"]);
        Unit::create(["name" => "G", "description" => "Gram"]);
        Unit::create(["name" => "TON", "description" => "Ton"]);

        Unit::create(["name" => "L", "description" => "Liter"]);
        Unit::create(["name" => "ML", "description" => "Milliliter"]);

        Unit::create(["name" => "PCS", "description" => "Pieces"]);
        Unit::create(["name" => "BOX", "description" => "Box"]);
        Unit::create(["name" => "PACK", "description" => "Pack"]);
        Unit::create(["name" => "SET", "description" => "Set"]);

        Unit::create(["name" => "PAIR", "description" => "Pair"]);
        Unit::create(["name" => "DOZEN", "description" => "Dozen"]);

        Unit::create(["name" => "M", "description" => "Meter"]);
        Unit::create(["name" => "CM", "description" => "Centimeter"]);
        Unit::create(["name" => "M2", "description" => "Square Meter"]);

        Unit::create(["name" => "BAG", "description" => "Bag"]);
        Unit::create(["name" => "BOTTLE", "description" => "Bottle"]);
        Unit::create(["name" => "CAN", "description" => "Can"]);
        Unit::create(["name" => "CONTAINER", "description" => "Container"]);
    }
}
