<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LogicException;
use Tests\Resources\Traits\IndexTrait;
use Tests\TestCase;

abstract class AbstractResourceTest extends TestCase
{
    use IndexTrait;

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

    public static function resourceRoute(string $action, array $params = []): string
    {
        return route(sprintf('v1.%s.%s', static::resourceType(), $action), $params);
    }
}
