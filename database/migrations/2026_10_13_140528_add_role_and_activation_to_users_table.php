<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('paciente')->after('email');
            $table->string('activation_token')->nullable()->unique()->after('role');
            $table->timestamp('activation_expires_at')->nullable()->after('activation_token');

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropUnique(['activation_token']);
            $table->dropColumn(['role', 'activation_token', 'activation_expires_at']);
        });
    }
};