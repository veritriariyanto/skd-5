<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users'; // Default table name for users

    protected $fillable = [
        'name',
        'email',
        'password',
        'img',
        'role',
        'otp',
        'otp_generated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_generated_at' => 'datetime',
        ];
    }
    public function generateOTP()
    {
        $this->otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_generated_at = now();
        $this->save();

        // Send OTP email
        $this->notify(new \App\Notifications\OtpNotification());
    }

    public function verifyOTP($code)
    {
        if (
            $this->otp === $code &&
            Carbon::parse($this->otp_generated_at)->addMinutes(15)->isAfter(now())
        ) {
            $this->is_verified = true;
            $this->otp = null;
            $this->save();
            return true;
        }
        return false;
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
