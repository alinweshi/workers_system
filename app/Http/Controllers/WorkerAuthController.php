<?php
namespace App\Http\Controllers;

use App\Http\Requests\WorkerRequest\LoginRequest;
use App\Http\Requests\WorkerRequest\RegisterRequest;
use App\Models\User;
use App\Models\Worker;
use App\Services\WorkerService\LoginService;
use App\Services\WorkerService\RegisterService;
use Illuminate\Support\Facades\Auth;

class WorkerAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    protected $loginService;
    protected $registerService;
    public function __construct(LoginService $loginService, RegisterService $registerService)
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register', 'Verify']]);
        $this->loginService = $loginService;
        $this->registerService = $registerService;
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return $this->loginService->login($request);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        return $this->registerService->register($request);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function Verify($token)
{
    try {
        // Your existing code...
        $worker = Worker::whereVerificationToken($token)->first();

        if ($worker) {
            // Your existing code...
            $worker->verification_token = null;
            $worker->email_verified_at = now();
            $worker->save();
            return response()->json(["message" => "Your account is verified successfully"]);
        }

        return response()->json(['message' => 'Invalid Token'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}


    public function logout()
    {
        auth()->guard('worker')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->guard('worker')->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->guard('worker')->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('worker')->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user(),
        ]);
    }
}
