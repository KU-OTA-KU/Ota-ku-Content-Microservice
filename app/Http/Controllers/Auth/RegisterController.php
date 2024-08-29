<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\IpAddress; // Не забудь добавить этот импорт
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Traits\HasIpAddresses;

class RegisterController extends Controller
{
    use HasIpAddresses;

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     description="Registers a new user in the system and returns a success message.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data for registration",
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="Full name of the user"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="User's email address"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="Password for the new account"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123", description="Confirmation of the password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Your account has been created!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         ),
     *         @OA\Examples(
     *             example="Validation Error",
     *             summary="Sample validation error response",
     *             value={
     *                 "errors": {
     *                     "email": {"The email field is required."},
     *                     "password": {"The password field must be at least 8 characters."}
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred. Please try again.")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=false,
     *         description="Accepted response format (e.g., application/json)",
     *         @OA\Schema(type="string")
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ipRecord = IpAddress::firstOrCreate(['ip_address' => $request->ip()]);

        $user = User::create([
            'login' => $request->input('login'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user->ipAddresses()->attach($ipRecord->id);

        return response()->json(['status' => 'success', 'message' => 'Your account has been created!'], 200);
    }
}
