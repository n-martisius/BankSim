<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // actor user
            $table->string('message')->nullable();
            $table->string('event_type'); // e.g. 'transaction.created', 'user.role_changed'
            $table->string('event_level'); // e.g. 'info', 'warning', 'error'
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
