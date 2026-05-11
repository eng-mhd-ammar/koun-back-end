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
        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('branch_id')->unique()->constrained('branches')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('cascade')->onUpdate('cascade');
            $table->string('city');
            $table->string('street');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('details')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
