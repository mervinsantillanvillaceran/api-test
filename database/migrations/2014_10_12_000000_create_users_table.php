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
            $table->string('name')->default('');
            $table->string('user_name', 20)->default('');
            $table->string('avatar', 1000)->default('');
            $table->string('email')->unique();
            $table->string('user_role', 14)->default('user');
            $table->string('invitation_token', 255)->default('');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('');
            $table->timestamp('registered_at')->nullable();
            $table->string('pin', 6)->default('');
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
        Schema::dropIfExists('users');
    }
}
