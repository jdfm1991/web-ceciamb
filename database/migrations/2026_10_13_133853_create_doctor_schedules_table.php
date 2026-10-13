<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();
            $table->tinyInteger('day_of_week'); // 0=Domingo, 1=Lunes, ..., 6=Sábado
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('slot_duration_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['doctor_id', 'day_of_week', 'start_time']);
            $table->index(['doctor_id', 'day_of_week', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};