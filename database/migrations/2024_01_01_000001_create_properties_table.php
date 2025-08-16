<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_owner_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('type')->comment('apartment, house, room, etc.');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code')->nullable();
            $table->integer('max_guests');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('price_per_night', 10, 2);
            $table->json('amenities')->nullable();
            $table->json('photos')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('accommodation_owner_id');
            $table->index(['city', 'is_active']);
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};