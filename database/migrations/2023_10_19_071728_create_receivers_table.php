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
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('senders');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs('concat(first_name, \' \', last_name)');
            $table->string('email')->unique();
            $table->string('Home_number')->nullable();
            $table->string('Mobile_number')->unique();
            $table->string('Address');
            $table->foreignId('philprovince_id')->constrained('philprovinces');
            $table->foreignId('philcity_id')->constrained('philcities');
            $table->foreignId('philbarangay_id')->constrained('philbarangays');
            $table->string('zip_code');
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
        Schema::dropIfExists('receivers');
    }
};
