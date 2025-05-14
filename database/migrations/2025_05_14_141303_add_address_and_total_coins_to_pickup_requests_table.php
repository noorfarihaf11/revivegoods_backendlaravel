<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->string('address')->nullable()->after('scheduled_at');
            $table->integer('total_coins')->default(0)->after('address');
        });
    }

    public function down()
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropColumn(['address', 'total_coins']);
        });
    }
};
