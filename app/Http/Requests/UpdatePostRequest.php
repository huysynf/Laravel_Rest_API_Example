<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UpdatePostRequest extends FormRequest
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
            'name' => 'required',
            'body' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response =  new Response([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        throw (new ValidationException($validator,$response));
    }
}
