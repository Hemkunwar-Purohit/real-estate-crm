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
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained();
            $table->foreignId('property_id')->constrained();
            $table->foreignId('agent_id')->constrained('users');
            $table->dateTime('visit_datetime');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])
                ->default('scheduled');
            $table->text('feedback')->nullable();
            $table->enum('interest_level', ['high', 'medium', 'low', 'not_interested'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
