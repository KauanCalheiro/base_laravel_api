<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
            $table->string('method', 10);
            $table->string('path');
            $table->jsonb('query')->nullable();
            $table->jsonb('body')->nullable();
            $table->jsonb('headers')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->integer('response_status');
            $table->double('execution_time_in_ms')->nullable();
            $table->jsonb('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('logs');
    }
};
