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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('counseling_session_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->boolean('all_day')->default(false);
            $table->enum('event_type', ['session', 'appointment', 'reminder', 'personal', 'other'])->default('other');
            $table->string('location')->nullable();
            $table->string('color')->default('#3788d8'); // Hex color for calendar display
            $table->boolean('is_recurring')->default(false);
            $table->json('recurrence_rules')->nullable(); // For recurring events
            $table->datetime('reminder_at')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
