<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new Rating())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained((new Order())->getTable());
            $table->foreignId('store_id')->constrained((new Store())->getTable());
            $table->foreignId('customer_id')->constrained((new Customer())->getTable());
            $table->integer('rating');
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists((new Rating())->getTable());
    }
}
