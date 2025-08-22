<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyProductRequest extends FormRequest
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
			'document' => 'string',
            'code' => 'required|string',
			'batch' => 'string|nullable',
			'type_payment' => 'required|numeric',
			'location_type' => 'required|numeric',
			'location_id' => 'required|numeric',
			'provider_id' => 'required|numeric',
			'notation' => 'string|nullable',
        ];
    }
}