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
        Schema::create('donation_items', function (Blueprint $table) {
            $table->id('id_donationitem');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->unsignedBigInteger('id_category')->nullable();
            $table->foreign('id_category')->references('id_category')->on('categories');
            $table->string('name');
            $table->string('coins');
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'pickup_scheduled', 'picked_up', 'rejected'])->default('pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_items');
    }
};
