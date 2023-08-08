<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('registration_type');
            $table->date('date_of_birth')->nullable();
            $table->longText('intro')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['pending', 'active', 'in_active'])->default('pending');
            $table->string('referred_by')->nullable();
            $table->string('referred_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->enum('provider', ['email', 'google', 'facebook']);
            $table->text('provider_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
