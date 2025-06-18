<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            // Ubah TIMESTAMP → DATETIME
            $table->dateTime('scheduled_at')->change();
        });
    }

    public function down(): void
    {
        // Kembali ke TIMESTAMP (jika perlu rollback)
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->change();
        });
    }
};
