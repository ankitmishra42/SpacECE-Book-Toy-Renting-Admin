<?php

use App\Models\Transaction;
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
        Schema::table((new Transaction())->getTable(), function (Blueprint $table) {
            $table->boolean('is_withdraw')->default(false);
            $table->timestamp('accept')->nullable();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Transaction())->getTable(), function (Blueprint $table) {
            $table->dropColumn('is_withdraw', 'status', 'accept');
        });
    }
};
