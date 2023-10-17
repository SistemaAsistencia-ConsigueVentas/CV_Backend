<?php

declare(strict_types=1);

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function rules(): array {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ];
    }

    public function messages(): array {
        return [
            'username.required' => 'Por favor ingrese el usuario.',
            'password.required' => 'Por favor ingrese la contraseña.'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new ValidationException($validator, response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}

