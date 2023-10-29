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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('generated_invoice');
            $table->foreignId('transreference_id')->constrained();
            $table->foreignId('sender_id')->constrained();
            $table->foreignId('receiver_id')->constrained();
            $table->string('manual_invoice');
            $table->foreignId('servicetype_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('boxtype_id')->constrained();
            $table->bigInteger('quantity');
            $table->date('booked_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
