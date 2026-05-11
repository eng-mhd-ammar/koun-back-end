<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\DonationRequest\Enums\DeliveryStatus;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('donation_request_id')->nullable()->constrained('donation_requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('delivery_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');

            $table->tinyInteger('status')->default(DeliveryStatus::PENDING->value)->comment(DeliveryStatus::tableComment());

            $table->dateTime('picked_at')->nullable();
            $table->dateTime('delivered_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
