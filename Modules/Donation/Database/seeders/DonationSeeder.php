<?php

namespace Modules\Donation\Database\seeders;

use DB;
use Illuminate\Database\Seeder;
use Modules\Address\Models\State;
use Modules\DonationRequest\Models\Delivery;
use Modules\Auth\Models\User;
use Modules\Donation\Enums\DonationStatus;
use Modules\Donation\Models\Donation;
use Modules\Donation\Models\DonationItem;
use Modules\Donation\Models\DonationType;
use Modules\Donation\Models\Unit;
use Modules\DonationRequest\Enums\DeliveryStatus;
use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\DonationRequest;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake();

        /*
        |--------------------------------------------------------------------------
        | Required Data
        |--------------------------------------------------------------------------
        */

        $branches = DB::table('branches')->get();

        $units = Unit::query()->get();
        $donationTypes = DonationType::query()->get();

        // Users who have delivery role
        $deliveryUsers = User::role('delivery')->get();

        if ($branches->isEmpty()) {
            $this->command->error('No branches found.');
            return;
        }

        if ($units->isEmpty()) {
            $this->command->error('No units found.');
            return;
        }

        if ($donationTypes->isEmpty()) {
            $this->command->error('No donation types found.');
            return;
        }

        if ($deliveryUsers->isEmpty()) {
            $this->command->error('No delivery users found.');
            return;
        }

        DB::transaction(function () use (
            $faker,
            $branches,
            $units,
            $donationTypes,
            $deliveryUsers
        ) {

            /*
            |--------------------------------------------------------------------------
            | Create Donations
            |--------------------------------------------------------------------------
            */

            $donationItems = collect();

            for ($i = 1; $i <= 100; $i++) {

                /*
                |--------------------------------------------------------------------------
                | Sender Branch
                |--------------------------------------------------------------------------
                */

                $senderBranch = $branches->random();

                /*
                |--------------------------------------------------------------------------
                | Sender User
                |--------------------------------------------------------------------------
                */

                $senderUsers = DB::table('user_branches')
                    ->where('branch_id', $senderBranch->id)
                    ->pluck('user_id');

                if ($senderUsers->isEmpty()) {
                    continue;
                }

                $senderUserId = $senderUsers->random();

                /*
                |--------------------------------------------------------------------------
                | Donation Status
                |--------------------------------------------------------------------------
                */

                $status = $faker->randomElement([
                    DonationStatus::PENDING->value,
                    DonationStatus::APPROVED->value,
                    DonationStatus::APPROVED->value,
                    DonationStatus::APPROVED->value,
                    DonationStatus::REJECTED->value,
                ]);

                $donation = Donation::create([
                    'sender_branch_id' => $senderBranch->id,
                    'sender_user_id' => $senderUserId,

                    'title' => $faker->randomElement([
                        'تبرع بالمواد الغذائية',
                        'تبرع بالملابس',
                        'تبرع بالمستلزمات',
                        'تبرع بالأجهزة',
                        'تبرع بالأثاث',
                        'تبرع بالمواد الطبية',
                        'تبرع بالكتب والقرطاسية',
                    ]),

                    'description' => $faker->optional()->sentence(),

                    'status' => $status,

                    'sent_at' => $status !== DonationStatus::PENDING->value
                        ? $faker->dateTimeBetween('-6 months', 'now')
                        : null,

                    'notes' => $faker->optional()->sentence(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Donation Items
                |--------------------------------------------------------------------------
                */

                $itemsCount = $faker->numberBetween(1, 4);

                for ($j = 0; $j < $itemsCount; $j++) {

                    $quantity = $faker->numberBetween(5, 100);

                    /*
                    | Pending donation:
                    | Everything is still available.
                    |
                    | Approved donation:
                    | Some quantity may have already been distributed.
                    |
                    | Rejected donation:
                    | No remaining quantity.
                    */

                    if ($status === DonationStatus::REJECTED->value) {
                        $remainingQuantity = 0;
                    } elseif ($status === DonationStatus::PENDING->value) {
                        $remainingQuantity = $quantity;
                    } else {
                        $remainingQuantity = $faker->numberBetween(
                            0,
                            $quantity
                        );
                    }

                    $type = $donationTypes->random();
                    $unit = $units->random();

                    $item = DonationItem::create([
                        'donation_id' => $donation->id,
                        'unit_id' => $unit->id,
                        'donation_type_id' => $type->id,

                        'name' => $type->name,

                        'description' => $faker->optional()->sentence(),

                        'quantity' => $quantity,

                        'remaining_quantity' => $remainingQuantity,

                        'notes' => $faker->optional()->sentence(),
                    ]);

                    $donationItems->push($item);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Create Donation Requests
            |--------------------------------------------------------------------------
            */

            $availableItems = $donationItems
                ->filter(fn ($item) => $item->remaining_quantity > 0)
                ->values();

            if ($availableItems->isEmpty()) {
                return;
            }

            for ($i = 1; $i <= 50; $i++) {

                /*
                |--------------------------------------------------------------------------
                | Receiver Branch
                |--------------------------------------------------------------------------
                */

                $receiverBranch = $branches->random();

                /*
                |--------------------------------------------------------------------------
                | Receiver User
                |--------------------------------------------------------------------------
                */

                $receiverUsers = DB::table('user_branches')
                    ->where('branch_id', $receiverBranch->id)
                    ->pluck('user_id');

                if ($receiverUsers->isEmpty()) {
                    continue;
                }

                $receiverUserId = $receiverUsers->random();

                /*
                |--------------------------------------------------------------------------
                | Request Status
                |--------------------------------------------------------------------------
                */

                $status = $faker->randomElement([
                    DonationRequestStatus::PENDING->value,
                    DonationRequestStatus::APPROVED->value,
                    DonationRequestStatus::APPROVED->value,
                    DonationRequestStatus::REJECTED->value,
                ]);

                $request = DonationRequest::create([
                    'receiver_user_id' => $receiverUserId,
                    'receiver_branch_id' => $receiverBranch->id,

                    'status' => $status,

                    'notes' => $faker->optional()->sentence(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Request Items
                |--------------------------------------------------------------------------
                */

                $requestItemsCount = $faker->numberBetween(1, 3);

                $selectedItems = $availableItems
                    ->random(
                        min(
                            $requestItemsCount,
                            $availableItems->count()
                        )
                    );

                // random() returns model instead of collection when count = 1
                $selectedItems = collect($selectedItems);

                foreach ($selectedItems as $donationItem) {

                    $maxQuantity = max(
                        1,
                        (int) $donationItem->remaining_quantity
                    );

                    $requestedQuantity = $faker->numberBetween(
                        1,
                        $maxQuantity
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Approved Quantity
                    |--------------------------------------------------------------------------
                    */

                    if ($status === DonationRequestStatus::APPROVED->value) {

                        $approvedQuantity = $faker->numberBetween(
                            1,
                            $requestedQuantity
                        );

                        $receivedQuantity = 0;

                    } elseif ($status === DonationRequestStatus::REJECTED->value) {

                        $approvedQuantity = null;

                        $receivedQuantity = 0;

                    } else {

                        $approvedQuantity = null;

                        $receivedQuantity = 0;
                    }

                    DB::table('donation_request_items')->insert([
                        'donation_request_id' => $request->id,
                        'donation_item_id' => $donationItem->id,

                        'requested_quantity' => $requestedQuantity,

                        'approved_quantity' => $approvedQuantity,

                        'received_quantity' => $receivedQuantity,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Update Remaining Quantity
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $status === DonationRequestStatus::APPROVED->value
                        && $approvedQuantity > 0
                    ) {
                        $donationItem->remaining_quantity -= $approvedQuantity;

                        $donationItem->save();
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Delivery
                |--------------------------------------------------------------------------
                */

                if ($status === DonationRequestStatus::APPROVED->value) {

                    $deliveryStatus = $faker->randomElement([
                        DeliveryStatus::PENDING->value,
                        DeliveryStatus::PICKED_UP->value,
                        DeliveryStatus::IN_TRANSIT->value,
                        DeliveryStatus::DELIVERED->value,
                    ]);

                    $pickedAt = null;
                    $deliveredAt = null;

                    /*
                    |--------------------------------------------------------------------------
                    | Dates according to status
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $deliveryStatus === DeliveryStatus::PICKED_UP->value ||
                        $deliveryStatus === DeliveryStatus::IN_TRANSIT->value ||
                        $deliveryStatus === DeliveryStatus::DELIVERED->value
                    ) {
                        $pickedAt = $faker->dateTimeBetween(
                            '-30 days',
                            '-2 days'
                        );
                    }

                    if ($deliveryStatus === DeliveryStatus::DELIVERED->value) {
                        $deliveredAt = $faker->dateTimeBetween(
                            $pickedAt,
                            'now'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Delivery User
                    |--------------------------------------------------------------------------
                    */

                    $deliveryUser = $deliveryUsers->random();

                    Delivery::create([
                        'donation_request_id' => $request->id,

                        'delivery_id' => $deliveryUser->id,

                        'receiver_id' => $receiverUserId,

                        'status' => $deliveryStatus,

                        'picked_at' => $pickedAt,

                        'delivered_at' => $deliveredAt,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Received Quantities
                    |--------------------------------------------------------------------------
                    */

                    if ($deliveryStatus === DeliveryStatus::DELIVERED->value) {

                        DB::table('donation_request_items')
                            ->where('donation_request_id', $request->id)
                            ->update([
                                'received_quantity' => DB::raw(
                                    'approved_quantity'
                                ),
                                'updated_at' => now(),
                            ]);
                    }
                }
            }
        });

        $this->command->info('Donations seeded successfully.');
    }
}
