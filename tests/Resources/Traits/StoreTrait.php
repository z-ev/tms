<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Resources\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelJsonApi\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait StoreTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public static function model(): string;

    abstract public static function resourceType(): string;

    abstract public function testStore(): void;

    /**
     * @param array $data
     * @param int   $responseCode
     *
     * @return TestResponse
     */
    protected function storeAdminData(array $data, int $responseCode = Response::HTTP_CREATED): TestResponse
    {
        $this->actAs([User::ROLE_ADMIN]);

        $response = $this->storeDataResponse($data);

        $response->assertStatus($responseCode);

        return $response;
    }

    /**
     * @param array $data
     * @param int   $responseCode
     *
     * @return TestResponse
     */
    protected function storeUserData(array $data, int $responseCode = Response::HTTP_CREATED): TestResponse
    {
        $this->actAs([User::ROLE_CLIENT]);

        $response = $this->storeDataResponse($data);

        $response->assertStatus($responseCode);

        return $response;
    }

    /**
     * @param array $data
     *
     * @return TestResponse
     */
    protected function storeDataResponse(array $data): TestResponse
    {
        return $this
            ->jsonApi()
            ->expects(static::resourceType())
            ->withData($data)
            ->post(route(sprintf('v1.%s.store', static::resourceType())));
    }
}
