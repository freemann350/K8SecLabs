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
        Schema::create('environment_access', function (Blueprint $table) {
            $table->id('environment_access_id');
            $table->foreignId('environment_id')->constrained('environments', 'environment_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('address',50);
            $table->integer('access_port');
            $table->timestamp('last_access')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environment_access');
    }
};