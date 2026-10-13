<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:64'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'El token de activación es obligatorio.',
            'token.size' => 'El token de activación no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'token' => 'token de activación',
        ];
    }
}
