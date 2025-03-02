<?php

namespace App\Repositories;

use App\DTO\AuthDTO;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface AuthRepositoryInterface
{
    public function findUserByEmail(string $email): ?User;
    public function findToken(string $token): ?PersonalAccessToken;
    public function createAccessToken(User $user, string $deviceId): string;
    public function createRefreshToken(User $user, string $deviceId): string;
    public function updateTokenExpiration(PersonalAccessToken $token, \DateTime $expirationTime): void;
    public function deleteUserTokensByDevice(User|Authenticatable $user, string $deviceId): void;
    public function attemptLogin(AuthDTO $credentials): bool;
    public function refreshToken(PersonalAccessToken $token, User $user, string $deviceId): array;
}