<?php

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new User())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('mobile')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('password');
            $table->foreignId('profile_photo_id')->nullable()->constrained((new Media())->getTable());
            $table->enum('gender', config('enums.ganders'))->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('driving_lience')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists((new User())->getTable());
    }
}
