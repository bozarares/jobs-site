<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyContact extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country' => 'string|max:255',
            'state' => 'string|max:255',
            'town' => 'string|max:255',
            'address' => 'string|max:255',
            'phone_number' => 'string|max:20',
            'email' => 'string|email|max:255',
        ];
    }
}
