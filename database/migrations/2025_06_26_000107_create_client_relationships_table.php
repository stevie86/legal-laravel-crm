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
        Schema::create('client_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_client_id')->constrained('clients')->onDelete('cascade');
            $table->enum('relationship_type', [
                'spouse', 'partner', 'child', 'parent', 'sibling',
                'grandparent', 'grandchild', 'friend', 'colleague', 'other',
            ]);
            $table->text('description')->nullable();
            $table->boolean('is_emergency_contact')->default(false);
            $table->timestamps();

            $table->unique(['client_id', 'related_client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_relationships');
    }
};
