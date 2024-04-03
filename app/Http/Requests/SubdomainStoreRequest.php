<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SubdomainStoreRequest extends FormRequest
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
            'name'=>'required|alpha_num:ascii|max:63',
            'NETWORK_PASSPHRASE'=> 'required|string',
            'URI_REQUEST_SIGNING_KEY'=> 'required|string',
            'SIGNING_KEY'=> 'required|string', 
            'FEDERATION_SERVER'=> 'required|string',
            'WEB_AUTH_ENDPOINT'=> 'required|string', 
            'TRANSFER_SERVER'=> 'string',
            'CURRENCIES'=>'array'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'status' => false,
            'data' => $validator->errors(),
        ], 403);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
