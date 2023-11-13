<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Mews\Purifier\Facades\Purifier;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if ($this->has('description')) {
            $this->merge([
                'description' => Purifier::clean($this->description),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'logo' => 'string',
            'logo_extension' => 'string|in:png,jpg,jpeg,PNG,JPG,JPEG',
            'description' => 'string|max:2048',
            'country' => 'string|max:255',
            'state' => 'string|max:255',
            'town' => 'string|max:255',
            'address' => 'string|max:255',
            'phone_number' => 'string|max:20',
            'email' => 'string|email|max:255',
        ];
    }
}
