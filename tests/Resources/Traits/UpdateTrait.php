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
trait UpdateTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public static function model(): string;

    abstract public static function resourceType(): string;

    abstract public static function routeParamName(): string;

    abstract public function testUpdate(): void;

    /**
     * @param Model  $model
     * @param array  $data
     * @param string $role
     * @param int    $responseCode
     *
     * @return TestResponse
     */
    protected function updateWithRoleData(
        Model $model,
        array $data,
        string $role = User::ROLE_GUEST,
        int $responseCode = Response::HTTP_OK
    ): TestResponse
    {
        $this->actAs([$role]);
        $response = $this->updateDataResponse($data, $model);
        $response->assertStatus($responseCode);

        return $response;
    }

    /**
     * @param array $data
     * @param Model $model
     *
     * @return TestResponse
     */
    protected function updateDataResponse(array $data, Model $model): TestResponse
    {
        $route = route(
            sprintf('v1.%s.update', static::resourceType()),
            [static::routeParamName() => $model]
        );

        return $this
            ->jsonApi()
            ->expects(static::resourceType())
            ->withData($data)
            ->patch($route);
    }
}
