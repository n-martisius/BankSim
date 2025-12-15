<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogAffectedUsersTable extends Migration
{
    public function up()
    {
        Schema::create('audit_log_affected_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_log_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // affected user
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_log_affected_users');
    }
}
