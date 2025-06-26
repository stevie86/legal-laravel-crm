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
        Schema::create('session_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counseling_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Autor der Notiz
            $table->string('title');
            $table->longText('content');
            $table->enum('note_type', ['session_protocol', 'observation', 'homework', 'goal', 'other'])->default('session_protocol');
            $table->boolean('is_confidential')->default(true);
            $table->boolean('is_template')->default(false);
            $table->json('tags')->nullable(); // Für Kategorisierung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_notes');
    }
};
