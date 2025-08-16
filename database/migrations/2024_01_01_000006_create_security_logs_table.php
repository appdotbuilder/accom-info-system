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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('logged_by')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['access', 'incident', 'alert', 'maintenance', 'other']);
            $table->string('title');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->boolean('is_resolved')->default(false);
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            $table->index('property_id');
            $table->index('logged_by');
            $table->index('type');
            $table->index('severity');
            $table->index('is_resolved');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};