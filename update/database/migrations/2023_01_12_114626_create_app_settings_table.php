<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new AppSetting())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('logo')->nullable();
            $table->string('fav_icon')->nullable();
            $table->string('city')->nullable();
            $table->string('road')->nullable();
            $table->string('area')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('signature_id')->nullable();
            $table->string('currency')->default('$');
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
        Schema::dropIfExists((new AppSetting())->getTable());
    }
}
