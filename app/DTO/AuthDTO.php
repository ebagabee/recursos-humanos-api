<?php

namespace App\DTO;

use App\Http\Requests\AuthRequest;

readonly class AuthDTO
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    public static function fromRequest(AuthRequest $request)
    {
        return new self(
            email: $request->validated(key: "email"),
            password: $request->validated(key: "password"),
        );
    }

    public function toArray()
    {
        return [
            "email" => $this->email,
            "password" => $this->password,
        ];
    }

    public static function fromArray(array $data)
    {
        return new self(
            email: $data["email"],
            password: $data["password"],
        );
    }
}