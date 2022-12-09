<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Resources\Traits;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;

trait AuthTrait
{
    /**
     * @param array $roles
     */
    protected function actAs(array $roles): void
    {
        $user              = (new User());
        $user->settings    = User::SETTINGS;
        $user->roles       = $roles;
        $user->merchant_id = DatabaseSeeder::MOCK_MERCHANT_ID;

        Auth::setUser($user);
    }
}
