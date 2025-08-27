<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
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
			'company_id' => 'required',
			'local_id' => 'required',
			'customer_id' => 'required',
			'document_code' => 'string',
			'reference _document' => 'string',
			'currency' => 'required',
			'total' => 'required',
			'seller' => 'required',
			'serie' => 'required',
			'identifier' => 'string',
			'message' => 'string',
        ];
    }
}
