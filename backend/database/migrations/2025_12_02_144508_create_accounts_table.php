<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // owner
            $table->string('number')->unique(); // account number
            $table->string('name'); // account name
            $table->string('type'); // account type (e.g., savings, checking)
            $table->string('currency', 3)->default('EUR');
            $table->decimal('balance', 20, 4)->default(0); // high precision for money
            $table->enum('status', ['active', 'suspended', 'closed'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
