<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class OrderCancellationStatus extends ApiRequest
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
            'is_cancelled' => ['required', 'integer', 'in:0,1'],
            'cancellation_reason' => 'required_if:is_cancelled,0|string',
        ];
    }

}
