<?php

namespace App\Http\Requests\WorkerRequest;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends ApiRequest
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
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:workers',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'required|string|max:100|unique:workers',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required|string|max:100',
        ];
    }
}
