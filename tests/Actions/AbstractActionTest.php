<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Actions;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LaravelJsonApi\Testing\TestResponse;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

abstract class AbstractActionTest extends TestCase
{
    public static string $model;
    public static string $resourceType;

    /**
     * @return class-string<Model>
     */
    public static function model(): string
    {
        if (isset(static::$model)) {
            return static::$model;
        }

        throw new LogicException('The model class name must be set.');
    }

    public static function resourceType(): string
    {
        if (isset(static::$resourceType)) {
            return static::$resourceType;
        }

        throw new LogicException('The resource type must be set.');
    }

    public static function routeParamName(): string
    {
        return Str::singular(Str::replace('-', '_', static::resourceType()));
    }

    /**
     * @param string $method
     * @param string $actionName
     * @param array  $params
     * @param array  $expectedData
     * @param int    $code
     *
     * @return TestResponse
     */
    protected function actAsAdmin(
        string $method,
        string $actionName,
        array $params,
        array $expectedData,
        int $code = Response::HTTP_OK
    ): TestResponse {
        $this->actAs([User::ROLE_ADMIN]);

        return $this->doAssertAction($method, $actionName, $params, $expectedData, $code);
    }

    /**
     * @param string $method
     * @param string $actionName
     * @param array  $params
     * @param array  $expectedData
     * @param int    $code
     *
     * @return TestResponse
     */
    protected function doUserActionTest(
        string $method,
        string $actionName,
        array $params,
        array $expectedData,
        int $code = Response::HTTP_OK
    ): TestResponse {
        $this->actAs([User::ROLE_CLIENT]);

        return $this->doAssertAction($method, $actionName, $params, $expectedData, $code);
    }

    /**
     * @param string $method
     * @param string $actionName
     * @param array  $params
     * @param array  $expectedData
     * @param int    $code
     *
     * @return TestResponse
     */
    protected function doAssertAction(
        string $method,
        string $actionName,
        array $params,
        array $expectedData,
        int $code
    ): TestResponse {
        $response = $this->doActionTest($method, $actionName, $params);

        $response->assertStatusCode($code);

        if ($expectedData) {
            $this->assertDatabaseHas(Str::replace('-', '_', static::resourceType()), $expectedData);
        }

        return $response;
    }

    /**
     * @param string $method
     * @param string $actionName
     * @param array  $params
     *
     * @return TestResponse
     */
    protected function doActionTest(string $method, string $actionName, array $params): TestResponse
    {
        $response = $this
            ->jsonApi();

        // Todo Refactor it
        switch ($method) {
            case 'POST':
            case 'PATCH-WITH-ID':
                $route = route(
                    sprintf('v1.%s.%s', $this->resourceType(), $actionName),
                    $params[0]
                );
                $response->withData($params);
                $method = 'POST';

                break;
            case 'PATCH':
                $route = route(
                    sprintf('v1.%s.%s', $this->resourceType(), $actionName),
                );
                $response->withData($params);

                break;
            default:
                $route = route(
                    sprintf('v1.%s.%s', $this->resourceType(), $actionName),
                    $params
                );

                break;
        }

        return $response->expects($this->resourceType())
            ->{$method}($route);
    }
}
