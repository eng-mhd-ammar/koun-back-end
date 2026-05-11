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
        Schema::create('donation_requests', function (Blueprint $table): void {
            $table->id();

            // $table->foreignId('donation_id')->nullable()->constrained('donations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('receiver_user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('receiver_branch_id')->nullable()->constrained('branches')->onDelete('cascade')->onUpdate('cascade');

            $table->tinyInteger('status')->default(DonationRequestStatus::PENDING->value)->comment(DonationRequestStatus::tableComment());

            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_requests');
    }
};
