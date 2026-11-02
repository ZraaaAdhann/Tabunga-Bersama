<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('deposit_date');
            $table->decimal('amount', 15, 2);
            $table->string('proof_image');
            $table->enum('status', ['verified', 'pending', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index untuk performa query setoran harian
            $table->index(['project_id', 'user_id', 'deposit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_deposits');
    }
};