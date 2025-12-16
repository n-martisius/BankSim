<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['from_account_id']);
            $table->dropForeign(['to_account_id']);

            // Make columns nullable
            $table->unsignedBigInteger('from_account_id')->nullable()->change();
            $table->unsignedBigInteger('to_account_id')->nullable()->change();

            // Re-add foreign keys
            $table->foreign('from_account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreign('to_account_id')->references('id')->on('accounts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['from_account_id']);
            $table->dropForeign(['to_account_id']);

            // Make columns NOT NULL
            $table->unsignedBigInteger('from_account_id')->nullable(false)->change();
            $table->unsignedBigInteger('to_account_id')->nullable(false)->change();

            // Re-add foreign keys
            $table->foreign('from_account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreign('to_account_id')->references('id')->on('accounts')->cascadeOnDelete();
        });
    }
};
