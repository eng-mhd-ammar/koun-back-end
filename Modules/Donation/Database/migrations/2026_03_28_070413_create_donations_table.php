<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Donation\Enums\DonationStatus;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('sender_branch_id')->nullable()->constrained('branches')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('sender_user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('title');
            $table->string('description')->nullable();

            $table->tinyInteger('status')->default(DonationStatus::PENDING->value)->comment(DonationStatus::tableComment());

            $table->dateTime('sent_at')->nullable();

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
        Schema::dropIfExists('donations');
    }
};
