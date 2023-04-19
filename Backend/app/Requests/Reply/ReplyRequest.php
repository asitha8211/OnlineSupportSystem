<?php

namespace App\Requests\Reply;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReplyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reply' => 'required|string|max:1000',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()
        ], 400));
    }
}