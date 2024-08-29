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
     * Send OTP to the user's email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendOtp(Request $request): JsonResponse
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $otpCode = random_int(10000, 99999);

        Cache::put('otp_code_' . $user->id, $otpCode, now()->addMinutes(3));

        Mail::to($user->email)->send(new OtpMail($otpCode));

        return response()->json(['message' => 'OTP sent to email successfully.'], 200);
    }
}
