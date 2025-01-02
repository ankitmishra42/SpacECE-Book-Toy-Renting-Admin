<?php

use App\Models\DriverOrder;
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
        Schema::table((new DriverOrder())->getTable(), function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('assign_for')->nullable()->after('driver_id');
            $table->boolean('is_completed')->default(0)->after('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new DriverOrder())->getTable(), function (Blueprint $table) {
            $table->string('status')->default('pick-up');
            $table->dropColumn('assign_for');
            $table->dropColumn('is_completed');
        });
    }
};
