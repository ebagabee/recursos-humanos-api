<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\PersonalAccessToken;
use App\DTO\AuthDTO;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(
        protected User $userModel,
        protected PersonalAccessToken $personalAccessTokenModel
    ) {}

    public function findUserByEmail(string $email): ?User
    {
        return $this->userModel->where(column: "email", operator: $email)->first();
    }

    public function findToken(string $token): ?PersonalAccessToken
    {
        return $this->personalAccessTokenModel->findToken(token: $token);
    }

    public function createAccessToken(User $user, string $deviceId): string
    {
        return $user->createToken(
            name: "access_token_{$deviceId}",
            abilities: ["access"],
            expiresAt: Carbon::now()->addMinutes(value: 15)
        )->plainTextToken;
    }

    public function createRefreshToken(User $user, string $deviceId): string
    {
        return $user->createToken(
            name: "refresh_token_{$deviceId}",
            abilities: ["refresh"],
            expiresAt: Carbon::now()->addDays(value: 30)
        )->plainTextToken;
    }

    public function updateTokenExpiration(PersonalAccessToken $token, \DateTime $expirationTime): void
    {
        $token->update(attributes: ["expires_at" => $expirationTime]);
    }

    public function deleteUserTokensByDevice(User|Authenticatable $user, string $deviceId): void
    {
        $user->tokens()
            ->where(column: "name", operator: "like", value: "%_{$deviceId}")
            ->delete();
    }

    public function attemptLogin(AuthDTO $credentials): bool
    {
        return Auth::attempt(credentials: $credentials->toArray());
    }

    public function refreshToken(PersonalAccessToken $tokenModel, User $user, string $deviceId): array
    {
        return DB::transaction(callback: function () use ($tokenModel, $user, $deviceId): array {
            $this->updateTokenExpiration(token: $tokenModel, expirationTime: Carbon::now()->addMinutes(value: 2));

            $accessToken = $this->createAccessToken(user: $user, deviceId: $deviceId);
            $refreshToken = $this->createRefreshToken(user: $user, deviceId: $deviceId);

            return [
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
            ];
        });
    }
}
