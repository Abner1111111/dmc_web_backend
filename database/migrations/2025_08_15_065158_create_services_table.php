<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create or update migration
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('description');
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
