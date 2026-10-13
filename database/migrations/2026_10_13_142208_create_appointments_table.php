<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Datos del paciente (snapshot al momento de la solicitud)
            $table->string('patient_name');
            $table->string('patient_email');
            $table->string('patient_phone');

            // Relación opcional con usuario registrado
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Relación opcional con médico y especialidad
            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();

            $table->foreignId('specialty_id')
                ->nullable()
                ->constrained('specialties')
                ->nullOnDelete();

            // Fecha y hora preferidas
            $table->date('requested_date');
            $table->time('requested_time');

            // Mensaje del paciente
            $table->text('message')->nullable();

            // Estado y trazabilidad
            $table->string('status')->default('pendiente');

            // Reagendamiento (self-referencing)
            $table->foreignId('parent_appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();

            // Bloqueo temporal de 24 horas
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // Índices para consultas frecuentes
            $table->index('status');
            $table->index('requested_date');
            $table->index(['status', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};