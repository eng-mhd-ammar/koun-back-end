<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\DonationRequest\Enums\DonationRequestStatus;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donation_request_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('donation_request_id')->nullable()->constrained('donation-requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('donation_item_id')->nullable()->constrained('donation-items')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('requested_quantity')->default(1);
            $table->integer('approved_quantity')->nullable();
            $table->integer('received_quantity')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_request_items');
    }
};
