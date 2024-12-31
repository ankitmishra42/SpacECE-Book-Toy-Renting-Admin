<?php

use App\Models\InvoiceManage;
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
        Schema::table((new InvoiceManage())->getTable(), function (Blueprint $table) {
            $table->string('invoice_name')->after('type')->nullable()->default('invoice1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new InvoiceManage())->getTable(), function (Blueprint $table) {
            $table->dropColumn('invoice_name');
        });
    }
};
