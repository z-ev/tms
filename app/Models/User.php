<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_MERCHANT,
        self::ROLE_OPERATOR,
        self::ROLE_CLIENT,
    ];

    public const ROLE_ADMIN    = 'ROLE_TOMS_ADMIN';
    public const ROLE_MERCHANT = 'ROLE_TOMS_MERCHANT';
    public const ROLE_OPERATOR = 'ROLE_TOMS_OPERATOR';
    public const ROLE_CLIENT   = 'ROLE_TOMS_CLIENT';

    public const NOTIFY_EVENTS = [
        'order' => [
            'created',
            'updated',
            'refunded',
        ],
    ];

    public const NOTIFICATION_OPTIONS = [
        'telegram' => [
            'list'   => [],
            'events' => self::NOTIFY_EVENTS,
        ],
    ];

    public const SETTINGS = [
        'notification' => self::NOTIFICATION_OPTIONS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasOne<Merchant>
     */
    public function user(): HasOne
    {
        return $this->hasOne(Merchant::class, 'id', 'merchant_id');
    }
}
