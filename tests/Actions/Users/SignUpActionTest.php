<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Actions\Users;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Tests\Actions\AbstractActionTest;

class SignUpActionTest extends AbstractActionTest
{
    public static string $model        = User::class;
    public static string $resourceType = 'users';

    /** @test */
    public function testGuestCanCreateUser(): void
    {
        $user = User::factory()->make();

        $this->doUserActionTest(
            'post',
            'signUp',
            [
                'name'       => $user->name,
                'email'      => $user->email,
                'merchantId' => DatabaseSeeder::MOCK_MERCHANT_ID,
                'statusId'   => User::STATUS_ACTIVE,
                'password'   => 'password',
            ],
            [
                'name'        => $user->name,
                'email'       => $user->email,
                'merchant_id' => DatabaseSeeder::MOCK_MERCHANT_ID,
                'status_id'   => User::STATUS_ACTIVE,
            ]
        );
    }

    /** @test */
    public function testGuestGetToken(): void
    {
        $user = User::factory()->create();

        $this->doUserActionTest(
            'post',
            'signIn',
            [
                'email'    => $user->email,
                'password' => 'password',
            ],
            [
            ]
        );
    }
}
