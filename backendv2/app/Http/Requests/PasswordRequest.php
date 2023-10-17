<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'old_password' => ['required'],
            'password' => ['required'],
            'confirm_password'=> ['required', 'same:password']
        ];
    }
    public function messages(): array {
        return [
            'old_password.required' => 'Por favor ingrese la antigua contraseña',
            'password.required' => 'Por favor ingrese la nueva contraseña.',
            'confirm_password.required' => 'Por favor ingrese la confirmacion de contraseña.',
            'confirm_password.same' => 'Las contraseñas no coiciden'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new ValidationException($validator, response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
