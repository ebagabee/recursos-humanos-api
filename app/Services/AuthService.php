<?php

namespace App\Services;

use App\DTO\AuthDTO;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository
    ) {}

    public function login(AuthDTO $credentials) {
        
        if (!$this->authRepository->attemptLogin(credentials: $credentials)) {
            return null;
        }

        $user = $this->authRepository->findUserByEmail(email: $credentials->email);
        $deviceId = Str::uuid()->toString();

        $accessToken = $this->authRepository->createAccessToken(user: $user, deviceId: $deviceId);
        $refreshToken = $this->authRepository->createRefreshToken(user: $user, deviceId: $deviceId);

        return [
            "access_token" => $accessToken,
            "refresh_token" => $refreshToken,
            "user" => $user,
            "device_id" => $deviceId
        ];
    }
}