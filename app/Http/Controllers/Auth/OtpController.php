<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Cache;

class OtpController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/generate-otp",
     *     summary="Generate OTP and send to the user's email",
     *     description="This endpoint generates a one-time password (OTP) and sends it to the user's email address.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP sent successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User with this email does not exist",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User with this email does not exist")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="OTP already sent. Please wait before requesting again.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP already sent. Please wait before requesting again.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function generateOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => __('otp.user_not_found')], 404);
        }

        if (Cache::has('otp_code_' . $user->id)) {
            return response()->json(['message' => __('otp.otp_already_sent')], 429);
        }

        $otpCode = random_int(10000, 99999);

        Cache::put('otp_code_' . $user->id, $otpCode, now()->addMinutes(3));

        Mail::to($user->email)->send(new OtpMail($otpCode));

        return response()->json(['message' => __('otp.otp_sent_success')], 200);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:5',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => __('otp.user_not_found')], 400);
        }

        if (Cache::has('otp_code_' . $user->id)) {
            return response()->json(['message' => __('otp.otp_verify_success')], 200);
        } else {
            return response()->json(['message' => __('otp.otp_verify_failed')], 400 );
        }
    }
}
