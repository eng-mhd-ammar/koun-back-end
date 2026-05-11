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
        Schema::create('branches', function (Blueprint $table): void {
            $table->id();

            $table->string('name');
            $table->string('description')->nullable();

            $table->foreignId('institution_id')->nullable()->constrained('institutions')->onDelete('cascade')->onUpdate('cascade');

            $table->string('phone');
            $table->string('email');

            $table->boolean('is_main_branch')->default(false);


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
