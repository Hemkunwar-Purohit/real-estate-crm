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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('source')->nullable();
            $table->string('status')->default('new');
            $table->string('property_type')->nullable(); // interested in
            $table->enum('listing_type', ['buy', 'rent'])->default('buy');
            $table->string('budget_min')->nullable();
            $table->string('budget_max')->nullable();
            $table->string('preferred_city')->nullable();
            $table->string('preferred_locality')->nullable();
            $table->text('requirements')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('converted_client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->boolean('is_converted')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
