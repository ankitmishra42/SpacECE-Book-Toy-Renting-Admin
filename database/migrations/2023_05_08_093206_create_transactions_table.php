<?php

use App\Models\Order;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Wallet;
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
        Schema::create((new Transaction())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class)->nullable()->constrained();
            $table->foreignIdFor(Wallet::class)->constrained();
            $table->foreignIdFor(Order::class)->nullable()->constrained();
            $table->float('amount');
            $table->bigInteger('transition_id')->nullable();
            $table->string('transition_type')->default('credit');
            $table->string('purpose');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Transaction())->getTable());
    }
};
