<?php

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new Order())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->string('prefix')->nullable();
            $table->foreignIdFor(Customer::class)->constrained();
            $table->foreignIdFor(Store::class)->constrained();
            $table->foreignIdFor(Coupon::class)->nullable()->constrained();
            $table->date('pick_date');
            $table->date('delivery_date')->nullable();
            $table->string('pick_hour')->nullable();
            $table->string('delivery_hour')->nullable();
            $table->float('payable_amount');
            $table->float('total_amount');
            $table->float('discount')->default(0);
            $table->float('delivery_charge')->default(0);
            $table->string('payment_status');
            $table->string('order_status');
            $table->string('payment_type')->nullable();
            $table->foreignIdFor(Address::class)->constrained();
            $table->longText('instruction')->nullable();
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
        Schema::dropIfExists((new Order())->getTable());
    }
}
