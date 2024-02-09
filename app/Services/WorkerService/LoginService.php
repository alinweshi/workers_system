<?php

namespace App\Services\WorkerService;

use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoginService
{
    protected $model;

    public function __construct(Worker $model)
    {
        $this->model = $model;
    }
    public function validateLogin($request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }
    public function CheckToken($validator)
    {
        if (!$token = auth()->guard('worker')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }
    public function getStatus($email, $token)
    {
        $worker = $this->model->where('email', $email)->first();
        if (!$worker) {
            return response()->json(['error' => 'incorrect data'], 404);
        }
        $status = $worker->status;
        return $status;

    }
    public function is_verified($email)
    {
        $worker = $this->model->where('email', $email)->first();
        if (!$worker) {
            return response()->json(['error' => 'incorrect data'], 404);
        }
        $verified = $worker->email_verified_at;

        return $verified;
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('worker')->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user(),
        ]);
    }

    public function login(Request $request)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Validate the login request
            $data = $this->validateLogin($request);

            // Check the token
            $token = $this->CheckToken($data);

            // Create a new token
            $token = $this->createNewToken($token);

            // Get the user status
            $status = $this->getStatus($request->email, $token);

            // Check if the account is verified
            if ($this->is_verified($request->email) == null) {
                // If the account is not verified, rollback the transaction and return a response
                DB::rollBack();
                return response()->json(['message' => 'Your account is not verified'], 200);
            } elseif ($status == 1) {
                // If the account is verified and active, commit the transaction and return the response
                DB::commit();
                return response()->json(['token' => $token, 'status' => $status], 200);
            }

            // If the account is verified but inactive, rollback the transaction and return a response
            DB::rollBack();
            return response()->json(['status' => 'Inactive'], 200);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction and handle the exception
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
