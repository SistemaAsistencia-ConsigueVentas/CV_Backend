<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'username' => 'string|max:191',
            'name' => 'required|string|max:191',
            'surname' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'string|min:8',
            'status' => 'bool',
            'dni' => 'required|string|max:20|unique:users',
            'position_id' => 'required|int|max:191',
            'cellphone' => 'required|string|max:11',
            'shift' => 'required|string|max:191',
            'birthday' => 'required|date|max:191',
            'image' => 'required',
            'date_start' => 'required|date|max:191',
            'date_end' => 'required|date|max:191',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'surname.required' => 'El apellido es obligatorio.',
            'surname.string' => 'El apellido debe ser una cadena de texto.',
            'surname.max' => 'El apellido no debe exceder los 191 caracteres.',

            'email.required' => 'El email es obligatorio.',
            'email.string' => 'El email debe ser una cadena de texto.',
            'email.email' => 'Debes introducir un email válido.',
            'email.max' => 'El email no debe exceder los 191 caracteres.',
            'email.unique' => 'Este email ya está en uso.',

            'position_id.required' =>'La posicion es requerida',

            'dni.required' => 'El DNI es obligatorio.',
            'dni.max' => 'El DNI no debe exceder los 20 caracteres.',
            'dni.unique' => 'Este DNI ya está registrado.',

            'cellphone.required' => 'El número de teléfono es obligatorio.',
            'cellphone.max' => 'El número de teléfono no debe exceder los 11 caracteres.',
            'cellphone.unique' => 'Este número de teléfono ya está registrado.',

            'shift.required' => 'El turno es obligatorio.',
            'shift.max' => 'El turno no debe exceder los 191 caracteres.',

            'birthday.required' => 'La fecha de nacimiento es obligatoria.',
            'birthday.date' => 'Debes introducir una fecha de nacimiento válida.',
            'birthday.max' => 'La fecha de nacimiento no debe exceder los 191 caracteres.',

            'image.required' => 'La imagen es obligatoria.',
            'image.max' => 'La imagen no debe exceder los 191 caracteres.',

            'date_start.required' => 'La fecha de inicio es obligatoria.',
            'date_start.date' => 'Debes introducir una fecha de inicio válida.',
            'date_start.max' => 'La fecha de inicio no debe exceder los 191 caracteres.',

            'date_end.required' => 'La fecha de finalización es obligatoria.',
            'date_end.date' => 'Debes introducir una fecha de finalización válida.',
            'date_end.max' => 'La fecha de finalización no debe exceder los 191 caracteres.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new ValidationException($validator, response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
