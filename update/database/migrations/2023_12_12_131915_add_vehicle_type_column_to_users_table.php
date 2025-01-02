<?php

use App\Models\User;
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
        Schema::table((new User())->getTable(), function (Blueprint $table) {
            $table->string('vehicle_type')->nullable()->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new User())->getTable(), function (Blueprint $table) {
            $table->dropColumn('vehicle_type');
        });
    }
};
