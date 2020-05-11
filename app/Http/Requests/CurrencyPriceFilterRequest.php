<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CurrencyPriceFilterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'valute' => 'nullable|string|max:10',
            'from' => 'nullable|dateFormat:d.m.Y',
            'to' => 'nullable|dateFormat:d.m.Y',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->messages(),
                'msg'=>'Validation error'
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }


}
