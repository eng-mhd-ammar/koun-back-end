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

        /*
        |--------------------------------------------------------------------------
        | Validate Required Data
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $threeMonthsAgo = now()->subMonths(3);
        $now = now();

        DB::transaction(function () use (
            $faker,
            $branches,
            $units,
            $donationTypes,
            $deliveryUsers,
            $threeMonthsAgo,
            $now
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

                /*
                |--------------------------------------------------------------------------
                | Donation Created At
                |--------------------------------------------------------------------------
                */

                $createdAt = $faker->dateTimeBetween(
                    $threeMonthsAgo,
                    $now
                );

                /*
                |--------------------------------------------------------------------------
                | Donation Sent At
                |--------------------------------------------------------------------------
                */

                $sentAt = null;

                if ($status !== DonationStatus::PENDING->value) {
                    $sentAt = $faker->dateTimeBetween(
                        $createdAt,
                        $now
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Create Donation
                |--------------------------------------------------------------------------
                */

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

                    'sent_at' => $sentAt,

                    'notes' => $faker->optional()->sentence(),

                    'created_at' => $createdAt,

                    'updated_at' => $createdAt,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Donation Items
                |--------------------------------------------------------------------------
                */

                $itemsCount = $faker->numberBetween(1, 4);

                for ($j = 0; $j < $itemsCount; $j++) {

                    /*
                    |--------------------------------------------------------------------------
                    | Quantity
                    |--------------------------------------------------------------------------
                    */

                    $quantity = $faker->numberBetween(5, 100);

                    /*
                    |--------------------------------------------------------------------------
                    | Remaining Quantity
                    |--------------------------------------------------------------------------
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

                    /*
                    |--------------------------------------------------------------------------
                    | Type & Unit
                    |--------------------------------------------------------------------------
                    */

                    $type = $donationTypes->random();

                    $unit = $units->random();

                    /*
                    |--------------------------------------------------------------------------
                    | Create Donation Item
                    |--------------------------------------------------------------------------
                    */

                    $item = DonationItem::create([
                        'donation_id' => $donation->id,

                        'unit_id' => $unit->id,

                        'donation_type_id' => $type->id,

                        'name' => $type->name,

                        'description' => $faker->optional()->sentence(),

                        'quantity' => $quantity,

                        'remaining_quantity' => $remainingQuantity,

                        'notes' => $faker->optional()->sentence(),

                        'created_at' => $createdAt,

                        'updated_at' => $createdAt,
                    ]);

                    $donationItems->push($item);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Available Donation Items
            |--------------------------------------------------------------------------
            */

            $availableItems = $donationItems
                ->filter(
                    fn ($item) =>
                        $item->remaining_quantity > 0
                )
                ->values();

            if ($availableItems->isEmpty()) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Create Donation Requests
            |--------------------------------------------------------------------------
            */

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

                /*
                |--------------------------------------------------------------------------
                | Select Donation Items
                |--------------------------------------------------------------------------
                */

                $requestItemsCount = $faker->numberBetween(1, 3);

                $selectedItems = $availableItems->random(
                    min(
                        $requestItemsCount,
                        $availableItems->count()
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | random() returns model when count = 1
                |--------------------------------------------------------------------------
                */

                $selectedItems = collect($selectedItems);

                /*
                |--------------------------------------------------------------------------
                | Request Created At
                |--------------------------------------------------------------------------
                |
                | Make sure the request is created after the selected
                | donation item was created.
                |
                */

                $oldestItemDate = $selectedItems
                    ->min(
                        fn ($item) => $item->created_at
                    );

                $requestCreatedAt = $faker->dateTimeBetween(
                    $oldestItemDate,
                    $now
                );

                /*
                |--------------------------------------------------------------------------
                | Create Donation Request
                |--------------------------------------------------------------------------
                */

                $request = DonationRequest::create([
                    'receiver_user_id' => $receiverUserId,

                    'receiver_branch_id' => $receiverBranch->id,

                    'status' => $status,

                    'notes' => $faker->optional()->sentence(),

                    'created_at' => $requestCreatedAt,

                    'updated_at' => $requestCreatedAt,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Request Items
                |--------------------------------------------------------------------------
                */

                foreach ($selectedItems as $donationItem) {

                    /*
                    |--------------------------------------------------------------------------
                    | Maximum Requested Quantity
                    |--------------------------------------------------------------------------
                    */

                    $maxQuantity = max(
                        1,
                        (int) $donationItem->remaining_quantity
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Requested Quantity
                    |--------------------------------------------------------------------------
                    */

                    $requestedQuantity = $faker->numberBetween(
                        1,
                        $maxQuantity
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Approved / Received Quantity
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $status ===
                        DonationRequestStatus::APPROVED->value
                    ) {

                        $approvedQuantity = $faker->numberBetween(
                            1,
                            $requestedQuantity
                        );

                        $receivedQuantity = 0;

                    } elseif (
                        $status ===
                        DonationRequestStatus::REJECTED->value
                    ) {

                        $approvedQuantity = null;

                        $receivedQuantity = 0;

                    } else {

                        $approvedQuantity = null;

                        $receivedQuantity = 0;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Create Request Item
                    |--------------------------------------------------------------------------
                    */

                    DB::table('donation_request_items')->insert([
                        'donation_request_id' => $request->id,

                        'donation_item_id' => $donationItem->id,

                        'requested_quantity' => $requestedQuantity,

                        'approved_quantity' => $approvedQuantity,

                        'received_quantity' => $receivedQuantity,

                        'created_at' => $requestCreatedAt,

                        'updated_at' => $requestCreatedAt,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Update Remaining Quantity
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $status ===
                        DonationRequestStatus::APPROVED->value
                        && $approvedQuantity > 0
                    ) {

                        $donationItem->remaining_quantity -=
                            $approvedQuantity;

                        $donationItem->save();
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Delivery
                |--------------------------------------------------------------------------
                */

                if (
                    $status ===
                    DonationRequestStatus::APPROVED->value
                ) {

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
                    | Picked At
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $deliveryStatus ===
                        DeliveryStatus::PICKED_UP->value

                        || $deliveryStatus ===
                        DeliveryStatus::IN_TRANSIT->value

                        || $deliveryStatus ===
                        DeliveryStatus::DELIVERED->value
                    ) {

                        $pickedAt = $faker->dateTimeBetween(
                            $requestCreatedAt,
                            $now
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Delivered At
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $deliveryStatus ===
                        DeliveryStatus::DELIVERED->value
                    ) {

                        $deliveredAt = $faker->dateTimeBetween(
                            $pickedAt,
                            $now
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Delivery User
                    |--------------------------------------------------------------------------
                    */

                    $deliveryUser = $deliveryUsers->random();

                    /*
                    |--------------------------------------------------------------------------
                    | Create Delivery
                    |--------------------------------------------------------------------------
                    */

                    Delivery::create([
                        'donation_request_id' => $request->id,

                        'delivery_id' => $deliveryUser->id,

                        'receiver_id' => $receiverUserId,

                        'status' => $deliveryStatus,

                        'picked_at' => $pickedAt,

                        'delivered_at' => $deliveredAt,

                        'created_at' => $requestCreatedAt,

                        'updated_at' => $requestCreatedAt,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Received Quantities
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $deliveryStatus ===
                        DeliveryStatus::DELIVERED->value
                    ) {

                        DB::table('donation_request_items')
                            ->where(
                                'donation_request_id',
                                $request->id
                            )
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

        $this->command->info(
            'Donations seeded successfully.'
        );
    }
}
