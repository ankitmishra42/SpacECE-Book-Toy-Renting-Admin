<?php

use App\Models\Additional;
use App\Models\Service;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new Additional())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained((new Service())->getTable());
            $table->foreignId('store_id')->constrained((new Store())->getTable());
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->float('price');
            $table->boolean('is_active')->default(false);
            $table->longText('description')->nullable();
            $table->longText('description_bn')->nullable();
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
        Schema::dropIfExists((new Additional())->getTable());
    }
}
