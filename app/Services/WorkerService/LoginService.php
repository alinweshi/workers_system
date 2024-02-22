<?php

namespace App\Services\WorkerService;

use App\Models\Worker;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoginService
{
    protected $model;

    public function __construct(Request $request)
    {
        $this->model = new Worker();
        $this->request = $request;
    }

    public function validateLogin($request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // Return the validated data instead of the request object
        return $validator;
    }

    public function checkToken($data)
    {
        if (!$token = auth()->guard('worker')->attempt($data)) {
            // dd($token, auth()->guard('worker')->attempt($data));
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $token;
    }

    public function getStatus($email)
    {
        $worker = $this->model->where('email', $email)->first();
        if (!$worker) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return $worker->status;
    }

    public function isVerified($email)
    {
        $worker = $this->model->where('email', $email)->first();
        if (!$worker) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return $worker->email_verified_at !== null;
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

    public function login($request)
    {
        $validator = $this->validateLogin($request);
        $data = $validator->validated(); // Access validated data
        $token = $this->checkToken($data);
        try {
            DB::beginTransaction();
            if (!$token instanceof JsonResponse) {
                $status = $this->getStatus($request->email);
                if ($status == 0) {
                    return response()->json(['message' => 'Your account is inactive'], 200);
                }
                if (!$this->isVerified($request->email)) {
                    return response()->json(['message' => 'Your account is not verified'], 200);
                }
                return $this->createNewToken($token);
            }
            DB::commit();
            return $token; // Return error response from token check
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);

        }

    }
}
