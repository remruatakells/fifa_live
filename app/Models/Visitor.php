<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'mobile',
    'email',
    'api_key_hash',
    'api_key_suffix',
    'ip_address',
    'user_agent',
    'is_blocked',
    'blocked_at',
    'block_reason',
    'last_seen_at',
])]
class Visitor extends Model
{
    protected function casts(): array
    {
        return [
            'is_blocked' => 'boolean',
            'blocked_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }
}
