<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Mews\Purifier\Facades\Purifier;

class StoreCompanyRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:companies,name',
            'code' => 'required|string|max:100|unique:companies,code',
            'logo' => 'required|string',
            'logo_extension' => 'required|string|in:png,jpg,jpeg,PNG,JPG,JPEG',
            'description' => 'required|string|max:2048',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
        ];
    }
}
