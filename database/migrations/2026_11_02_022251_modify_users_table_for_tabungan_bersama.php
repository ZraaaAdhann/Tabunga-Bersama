<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone_number')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone_number');
            $table->string('avatar')->nullable()->after('birth_date');
            $table->string('google_id')->nullable()->after('password');
            $table->enum('registration_step', [1, 2, 3, 'completed'])->default('completed');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'phone_number', 'birth_date', 'avatar', 'google_id', 'registration_step']);
        });
    }
};