<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Actions\Users;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Actions\AbstractActionTest;

class SignInActionTest extends AbstractActionTest
{
    public static string $model        = User::class;
    public static string $resourceType = 'users';

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
        )->assertJsonStructure([
            'meta' => ['token'],
        ]);
    }

    /** @test */
    public function testGuestCantGetToken(): void
    {
        $user = User::factory()->create();

        $this->doUserActionTest(
            'post',
            'signIn',
            [
                'email'    => $user->email,
                'password' => 'wrong',
            ],
            [
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
