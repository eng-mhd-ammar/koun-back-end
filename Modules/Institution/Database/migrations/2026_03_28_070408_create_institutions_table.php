<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Institution\Enums\InstitutionType;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table): void {
            $table->id();
            $table->string('logo')->nullable();

            $table->string('name');
            $table->string('description')->nullable();

            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');

            $table->string('phone');
            $table->string('email');

            $table->tinyInteger('type')->default(InstitutionType::DONOR->value)->comment(InstitutionType::tableComment());

            $table->boolean('is_active')->default(false);

            $table->json('attachments');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
