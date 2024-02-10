<?php

namespace App\Http\Requests\WorkerRequest;

use App\Http\Requests\ApiRequest;

class VerificationRequest extends ApiRequest
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
            'token' => 'string|exists:workers,verification_token',
        ];
    }

}