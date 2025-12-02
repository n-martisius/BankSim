<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // initiating user id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // from account id
            $table->foreignId('from_account_id')->constrained('accounts')->cascadeOnDelete();

            // to account id
            $table->foreignId('to_account_id')->constrained('accounts')->cascadeOnDelete();

            $table->enum('type', ['deposit', 'withdrawal', 'transfer'])->index();
            $table->decimal('amount', 20, 4);

            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('completed'); // pending, completed, failed, cancelled
            // details/description
            $table->string('details')->nullable();
            // created at
            $table->timestamp('created_at')->useCurrent();
            // updated at
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['from_account_id']);
            $table->index(['to_account_id']);
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
