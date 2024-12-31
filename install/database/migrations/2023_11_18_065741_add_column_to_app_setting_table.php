<?php

use App\Models\AppSetting;
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
        Schema::table((new AppSetting())->getTable(), function (Blueprint $table) {
            $table->string('direction')->nullable()->default('ltr');
            $table->string('currency_position')->nullable()->default('prefix');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new AppSetting())->getTable(), function (Blueprint $table) {
            $table->dropColumn('direction', 'currency_position');
        });
    }
};
