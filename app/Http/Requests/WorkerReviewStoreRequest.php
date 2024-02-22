<?php

namespace App\Http\Requests;

class WorkerReviewStoreRequest extends ApiRequest
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
            'post_id' => 'required|integer|exists:posts,id',
            'client_id' => 'required|integer|exists:clients,id',
            'comment' => 'string|max:255',
            'rate' => 'required|integer|min:1|max:5',
        ];
    }
}
