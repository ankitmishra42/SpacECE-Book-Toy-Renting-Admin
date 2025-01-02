<?php

use App\Models\Media;
use App\Models\Product;
use App\Models\Service;
use App\Models\Store;
use App\Models\Variant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new Product())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Service::class)->constrained();
            $table->foreignIdFor(Variant::class)->constrained();
            $table->foreignIdFor(Store::class)->constrained();
            $table->foreignId('thumbnail_id')->nullable()->constrained((new Media())->getTable());
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->nullable();
            $table->float('discount_price')->nullable();
            $table->float('price');
            $table->boolean('is_active')->default(false);
            $table->bigInteger('order')->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists((new Product())->getTable());
    }
}
