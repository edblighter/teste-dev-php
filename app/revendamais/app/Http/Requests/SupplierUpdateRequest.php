<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierUpdateRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'type' => 'sometimes|in:CPF,CNPJ',
            'document' => 'sometimes|string|cpf_ou_cnpj',
            'email' => 'sometimes|string|email',
            'phone' => 'sometimes|string',
            'street' => 'sometimes|string|max:255',
            'post_code' => 'sometimes|string|max:20',
            'state'  => 'sometimes|string|max:100',
            'city'  => 'sometimes|string|max:100',
            'country' => 'sometimes|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
          'success'   => false,
          'message'   => 'Validation errors',
          'data'      => $validator->errors()
        ],400));
    }
}
