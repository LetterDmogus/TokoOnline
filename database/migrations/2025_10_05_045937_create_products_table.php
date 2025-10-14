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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Dish name
            $table->text('description')->nullable(); // Description (optional)
            $table->decimal('price', 8, 2); // Price with 2 decimals
            $table->unsignedInteger('category')->nullable(); // e.g. Accessory, Laptop
            $table->string('image_url')->nullable(); // Store image path or URL
            $table->boolean('available')->default(true); // Availability
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
