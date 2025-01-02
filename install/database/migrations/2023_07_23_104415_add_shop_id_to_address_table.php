<?php

use App\Models\Address;
use App\Models\Store;
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
        Schema::table((new Address())->getTable(), function (Blueprint $table) {
            $table->foreignIdFor(Store::class)->nullable()->after('customer_id')->constrained();
            $table->foreignId('customer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Address())->getTable(), function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
    }
};
