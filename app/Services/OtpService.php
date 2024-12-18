<?php

namespace App\Services;

use App\Models\Otp;
use Carbon\Carbon;

class OtpService
{
    public function generate(string $identifier): string
    {
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Otp::create([
            'identifier' => $identifier,
            'token' => $token,
            'validity' => 15, // 15 minutes
            'generated_at' => now(),
            'no_times_generated' => 1
        ]);

        return $token;
    }

    public function validate(string $identifier, string $token): bool
    {
        $otp = Otp::where('identifier', $identifier)
            ->where('token', $token)
            ->where('expired', false)
            ->latest()
            ->first();

        if (!$otp) {
            return false;
        }

        $otp->no_times_attempted += 1;

        if (Carbon::parse($otp->generated_at)->addMinutes($otp->validity) < now()) {
            $otp->expired = true;
            $otp->save();
            return false;
        }

        $otp->expired = true;
        $otp->save();
        return true;
    }
}