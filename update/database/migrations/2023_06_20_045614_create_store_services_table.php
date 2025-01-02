<?php

use App\Models\Service;
use App\Models\Store;
use App\Models\StoreService;
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
        Schema::create((new StoreService())->getTable(), function (Blueprint $table) {
            $table->foreignIdFor(Store::class)->constrained();
            $table->foreignIdFor(Service::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new StoreService())->getTable());
    }
};
