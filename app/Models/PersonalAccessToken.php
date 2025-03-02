<?php

namespace App\Models;

class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    protected $table = "personal_access_tokens";

    protected $casts = [
        "abilities" => "array",
        "expirest_at" => "datetime",
    ];

    protected $hidden = [
        "token",
    ];

    protected $dates = [
        "created_at",
        "updated_at",
        "expires_at",
    ];
}