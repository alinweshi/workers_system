<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

abstract class ApiRequest extends FormRequest
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
            //
        ];
    }
    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     $errors = (new ValidationException($validator))->errors();
    //     $newErrors = [];
    //     if (!empty($errors)) {
    //         foreach ($errors as $error => $message) {
    //             $newErrors[$error] = $message[0];
    //             // $newErrors[$error] = $message;
    //         }
    //     }
    //     throw new HttpResponseException(
    //         response()->json(['status' => 'error', 'error' => $newErrors], JsonResponse::HTTP_BAD_REQUEST),
    //     );
    //     // throw new \Illuminate\Validation\ValidationException($validator);
    // }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        try{
            $errors = (new ValidationException($validator))->errors();
            $newErrors = [];
            foreach ($errors as $error => $message) {
                // Append each error to the array
                $newErrors[$error] = $message;
            }
        }
catch(  \Exception $e){
    throw new HttpResponseException(
        response()->json(['status' => 'error', 'error' => $newErrors], JsonResponse::HTTP_BAD_REQUEST),
    );
}


        // throw new \Illuminate\Validation\ValidationException($validator);
    }

}
