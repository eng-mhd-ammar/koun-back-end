<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donation_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('donation_id')->nullable()->constrained('donations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('donation_type_id')->nullable()->constrained('donation_types')->onDelete('cascade')->onUpdate('cascade');

            $table->string('name');
            $table->string('description')->nullable();
            $table->string('quantity');
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
        Schema::dropIfExists('donation_items');
    }
};
