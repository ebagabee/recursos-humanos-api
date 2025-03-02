<?php

namespace App\Http\Controllers;

use App\DTO\AuthDTO;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function __construct(
        protected AuthService $authService
    ) {}
    
    public function login(AuthRequest $request): JsonResponse
    {
        $tokens = $this->authService->login(credentials: AuthDTO::fromRequest(request: $request));

        if (!$tokens) {
            return $this->errorResponse(message: "Autenticação Falhou", code: 401);
        }

        return $this->successResponse(payload: new AuthResource(resource: $tokens), message: "Logado com sucesso");
    }
}