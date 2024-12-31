<?php

use App\Models\Media;
use App\Models\Store;
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
        Schema::create((new Store())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_owner')->constrained((new User())->getTable());
            $table->string('name');
            $table->foreignId('logo_id')->nullable()->constrained((new Media())->getTable());
            $table->foreignId('banner_id')->nullable()->constrained((new Media())->getTable());
            $table->float('delivery_charge')->nullable()->default(0);
            $table->float('min_order_amount')->default(0);
            $table->float('max_order_amount')->default(0);
            $table->text('description')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->float('commission')->default(0);
            $table->string('prifix')->default('MV');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Store())->getTable());
    }
};
