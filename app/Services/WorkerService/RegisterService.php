<?php
namespace App\Services\WorkerService;

use App\Mail\verificationEmail;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

class RegisterService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Worker();
    }
    //validate the data
    public function validateRegister($request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if (!$validator->fails()) {
            $validator = $validator->validate();
            return $validator;
        }
        $error = throw new \Exception('Validation failed: ' . $validator->errors()->first());

    }

    public function generateToken($email)
    {
        $token = Str::random(60); // Generate a random string (60 characters)
        $base64Token = base64_encode($token); // Encode the token to make it URL-safe

        $worker = $this->model->whereEmail($email)->first();

        if ($worker) {
            $worker->verification_token = $base64Token;
            $worker->save();
        }

        return $base64Token;
    }

    public function storeData($validator, $request, $token)
    {
        $validatedData = $validator;
        $worker = Worker::create(array_merge(
            $validatedData, // Use $validatedData instead of $validator
            [
                'password' => bcrypt($request->password),
                'photo' => $request->file('photo')->store('public/images/clients'),
                'verification_token' => $token,
            ]
        ));

        if (!$worker) {
            return response()->json(['error' => 'incorrect data'], 401);
        }

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $worker,
        ], 201);
    }

    public function sendEmail($email, $token, $name)
    {
        Mail::to($email)->send(new verificationEmail($token, $name));

        return response()->json(['message' => 'Please check your email to verify your account'], 201);
    }
    public function register($request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $this->validateRegister($request);
            $token = $this->generateToken($request->email);
            $worker = $this->storeData($validatedData, $request, $token);
            $this->sendEmail($request->email, $token, $request->name);
            DB::commit();
            return response()->json(['message' => 'you have been registered,Please check your email to verify your account'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
