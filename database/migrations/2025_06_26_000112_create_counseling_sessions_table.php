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
        Schema::create('counseling_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Berater
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->enum('session_type', ['individual', 'couple', 'family', 'group'])->default('individual');
            $table->string('location')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('online_meeting_link')->nullable();
            $table->decimal('fee', 8, 2)->nullable();
            $table->boolean('fee_paid')->default(false);
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_sessions');
    }
};
