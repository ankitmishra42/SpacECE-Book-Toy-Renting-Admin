<?php

use App\Models\Product;
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
        Schema::table((new Product())->getTable(), function (Blueprint $table) {
            $table->string('qrcode')->nullable();
            $table->string('qrcode_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Product())->getTable(), function (Blueprint $table) {
            $table->dropColumn('qrcode_url', 'qrcode');
        });
    }
};
