<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class CLientRegisterRequest extends ApiRequest
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
            'email' => 'required|string|email|max:100|unique:clients',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'required|string|max:100|unique:clients',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required|string|max:100',
        ];
    }
}
