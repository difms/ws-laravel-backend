<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    public function save(array $options = [])
    {
        $changes = $this->getDirty();
        // Проверяем два изменения, так как одно из них это всегда поле updated_at
        if (! array_key_exists('last_used_at', $changes) || count($changes) > 2) {
            parent::save();
        }
        return false;
    }
}
