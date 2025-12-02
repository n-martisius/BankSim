<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->enum('role', ['admin', 'teller', 'customer'])->default('customer');
            $table->string('full_name')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->enum('status', ['active', 'suspended', 'closed'])->default('active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
