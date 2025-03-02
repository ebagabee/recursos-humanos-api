<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "access_token" => $this["access_token"],
            "refresh_token" => $this["refresh_token"],
            "token_type" => "Bearer",
            "expires_in" => 900, // 15 minutos
            "refresh_token_expires_in" => 2592000, // 30 dias
            "user" => new UserResource($this["user"]),
        ];
    }

    public static function collection($resource): AnonymousResourceCollection
    {
        return parent::collection($resource);
    }
}
