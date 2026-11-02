<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('category')->default('Couple Goals 🏠');
            $table->text('description')->nullable();
            $table->decimal('daily_target', 15, 2)->default(0);
            $table->decimal('total_target', 15, 2)->default(0);
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');
            $table->string('qris_image_path')->nullable();
            $table->enum('status', ['active', 'archived', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};