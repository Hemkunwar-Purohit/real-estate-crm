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
            $table->string('title');
            $table->string('property_type'); // from config
            $table->string('status')->default('available');
            $table->enum('listing_type', ['sale', 'rent']);
            $table->decimal('price', 15, 2);
            $table->string('currency', 5)->default('INR');
            $table->string('area')->nullable();       // e.g. "1200 sqft"
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->string('floor')->nullable();
            $table->string('city');
            $table->string('locality')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('rera_number')->nullable(); // Indian market
            $table->foreignId('owner_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('added_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
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
