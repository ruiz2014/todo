<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
			'name' => 'required|string',
			'company_name' => 'string',
			'document' => 'required|string',
			'address' => 'required|string',
			'ubigeo' => 'string',
			'sector_id' => 'required',
			'number_employees' => 'required',
			'number_subsidiary' => 'required',
        ];
    }
}
