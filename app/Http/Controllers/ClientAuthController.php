<?php
namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest\LoginRequest;
use App\Http\Requests\ClientRequest\RegisterRequest;
use App\Models\Client;
use App\Models\User;
use App\Services\ClientService\LoginService;
use App\Services\ClientService\RegisterService;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
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
        $this->middleware('auth:client', ['except' => ['login', 'register', 'Verify']]);
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
            $client = Client::where('verification_token', $token)->first();

            if ($client) {
                // Check if the client is already verified
                if ($client->email_verified_at !== null) {
                    return response()->json(['message' => 'Client is already verified']);
                }

                // Update the client's verification details
                $client->verification_token = null;
                $client->email_verified_at = now();
                $client->save();

                return response()->json(["message" => "Your account is verified successfully"]);
            }

            return response()->json(['message' => 'Invalid Token'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        auth()->guard('client')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->guard('client')->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->guard('client')->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token1
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('client')->factory()->getTTL() * 60,
            'user' => auth()->guard('client')->user(),
        ]);
    }
}
