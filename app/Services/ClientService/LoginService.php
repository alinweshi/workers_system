<?php

namespace App\Services\ClientService;

use App\Http\Requests\ClientRequest\LoginRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginService
{
    protected $model;
    protected $request;

    public function __construct(LoginRequest $request)
    {
        $this->model = new Client();
        $this->request = $request;
    }
    public function validateLogin($request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }
    public function CheckToken($data)
    {
        if (!$token = auth()->guard('client')->attempt($data->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }
    public function getStatus($email, $token)
    {
        $Client = $this->model->where('email', $email)->first();
        if (!$Client) {
            return response()->json(['error' => 'incorrect data'], 404);
        }
        $status = $Client->status;
        return $status;

    }
    public function is_verified($email)
    {
        $Client = $this->model->where('email', $email)->first();
        if (!$Client) {
            return response()->json(['error' => 'incorrect data'], 404);
        }
        $verified = $Client->email_verified_at;

        return $verified;
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('client')->factory()->getTTL() * 60,
            'user' => auth()->guard('client')->user(),
        ]);
    }
    public function login($request)
    {
        $data = $this->validateLogin($request);
        $token = $this->CheckToken($data);
        $token = $this->createNewToken($token);
        $status = $this->getStatus($request->email, $token);
        if ($this->is_verified($request->email) == null) {
            return response()->json(['message' => 'your account is not verified'], 200);
        } elseif ($status == 1) {
            return response()->json(['token' => $token, 'status' => $status], 200);
        }
        return response()->json(['status' => 'Inactive'], 200);
    }

}
