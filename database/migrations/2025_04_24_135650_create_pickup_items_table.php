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
        Schema::create('pickup_items', function (Blueprint $table) {
            $table->id('id_pickupitem');
            $table->unsignedBigInteger('id_pickupreq')->nullable();
            $table->foreign('id_pickupreq')->references('id_pickupreq')->on('pickup_requests');
            $table->unsignedBigInteger('id_donationitem')->nullable();
            $table->foreign('id_donationitem')->references('id_donationitem')->on('donation_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_items');
    }
};
