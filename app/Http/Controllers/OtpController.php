<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'identifier' => 'required|email' // or phone number validation
        ]);

        $token = $this->otpService->generate($request->identifier);

        // Here you would typically send the OTP via email or SMS
        // For testing, we'll just return it
        return response()->json([
            'message' => 'OTP generated successfully',
            'token' => $token
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'identifier' => 'required|email', // or phone number validation
            'token' => 'required|string|size:6'
        ]);

        $isValid = $this->otpService->validate(
            $request->identifier,
            $request->token
        );

        return response()->json([
            'message' => $isValid ? 'OTP verified successfully' : 'Invalid OTP',
            'success' => $isValid
        ]);
    }
}