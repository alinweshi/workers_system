<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class PostStatusRequest extends ApiRequest
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
            'post_id' => 'required|exists:posts,id',
            'status' => 'in:approved,rejected',
            'reject_reason' => 'required_if:status,rejected',
        ];
    }
}
