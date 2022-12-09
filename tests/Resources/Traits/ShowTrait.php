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
trait ShowTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public static function model(): string;

    abstract public static function resourceType(): string;

    abstract public static function routeParamName(): string;

    abstract public function testShow(): void;

    /**
     * @param Model  $model
     * @param array  $expectedData
     * @param string $role
     * @param int    $responseCode
     *
     * @return TestResponse
     */
    protected function showWithRoleData(
        Model $model,
        array $expectedData,
        string $role = User::ROLE_GUEST,
        int $responseCode = Response::HTTP_OK
    ): TestResponse {
        $this->actAs([$role]);

        $response = $this->doTestShowResponse($model);
        $response->assertStatusCode($responseCode);

        if ($response->getStatusCode() == Response::HTTP_OK) {
            $response->assertFetchedOne($expectedData);
        }

        return $response;
    }

    /**
     * @param Model $model
     *
     * @return TestResponse
     */
    protected function doTestShowResponse(Model $model): TestResponse
    {
        $route = route(
            sprintf('v1.%s.show', static::resourceType()),
            [static::routeParamName() => $model]
        );

        return $this
            ->jsonApi()
            ->expects(static::resourceType())
            ->get($route);
    }
}
