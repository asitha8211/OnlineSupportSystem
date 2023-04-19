<?php

namespace App\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class OpenTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'description' => 'required|string|max:1000',
            'phone_number' => 'required|string|min:3|max:25'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()
        ], 400));
    }

}