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
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs('concat(first_name, \' \', last_name)');
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('password')->default('password');
            $table->string('Home_number')->nullable();
            $table->string('Mobile_number');
            $table->string('Address');
            $table->foreignId('provincecan_id')->constrained('provincecans');
            $table->foreignId('citycan_id')->constrained('citycans');
            $table->string('postal_code');
            $table->text('notes')->nullable();
            $table->longText('docs')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senders');
    }
};
