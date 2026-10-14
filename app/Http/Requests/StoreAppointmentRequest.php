<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Cualquier visitante puede solicitar una cita.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'min:3', 'max:100'],
            'patient_email' => ['required', 'email:rfc', 'max:150'],
            'patient_phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'doctor_id' => ['nullable', 'integer', 'exists:doctors,id'],
            'specialty_id' => ['nullable', 'integer', 'exists:specialties,id'],
            'requested_date' => ['required', 'date', 'after_or_equal:today'],
            'requested_time' => ['required', 'date_format:H:i'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Mensajes personalizados de error.
     */
    public function messages(): array
    {
        return [
            'patient_name.required' => 'El nombre del paciente es obligatorio.',
            'patient_name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'patient_name.max' => 'El nombre no puede exceder 100 caracteres.',
            'patient_email.required' => 'El correo electrónico es obligatorio.',
            'patient_email.email' => 'El correo electrónico no es válido.',
            'patient_phone.required' => 'El teléfono es obligatorio.',
            'patient_phone.regex' => 'El teléfono solo puede contener números, espacios, +, -, paréntesis.',
            'doctor_id.exists' => 'El médico seleccionado no existe.',
            'specialty_id.exists' => 'La especialidad seleccionada no existe.',
            'requested_date.required' => 'La fecha es obligatoria.',
            'requested_date.after_or_equal' => 'La fecha debe ser hoy o posterior.',
            'requested_time.required' => 'La hora es obligatoria.',
            'requested_time.date_format' => 'La hora debe tener el formato HH:MM.',
            'message.max' => 'El mensaje no puede exceder 1000 caracteres.',
        ];
    }

    /**
     * Nombres legibles de los campos.
     */
    public function attributes(): array
    {
        return [
            'patient_name' => 'nombre del paciente',
            'patient_email' => 'correo electrónico',
            'patient_phone' => 'teléfono',
            'doctor_id' => 'médico',
            'specialty_id' => 'especialidad',
            'requested_date' => 'fecha',
            'requested_time' => 'hora',
            'message' => 'mensaje',
        ];
    }
}
