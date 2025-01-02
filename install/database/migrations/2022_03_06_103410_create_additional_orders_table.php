<?php

use App\Models\Additional;
use App\Models\AdditionalOrder;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new AdditionalOrder())->getTable(), function (Blueprint $table) {
            $table->foreignId('order_id')->constrained((new Order())->getTable());
            $table->foreignId('additional_id')->constrained((new Additional())->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists((new AdditionalOrder())->getTable());
    }
}
