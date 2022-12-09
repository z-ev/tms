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
trait DestroyTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public static function model(): string;

    abstract public static function resourceType(): string;

    abstract public static function routeParamName(): string;

    abstract public function testDestroy(): void;

    protected function destroyWithRoleData(
        Model $model,
        string $role = User::ROLE_GUEST,
        int $responseCode = Response::HTTP_NO_CONTENT
    ): TestResponse
    {
        $this->actAs([$role]);
        $this->assertDatabaseHas((new (static::model()))->getTable(), [
            'id' => $model->id,
        ]);
        $response = $this->doTestDestroyResponse($model);

        if ($response->getStatusCode() == Response::HTTP_LOCKED) {
            $response->assertNoContent();
            $this->assertDatabaseMissing((new (static::model()))->getTable(), [
                'id' => $model->id,
            ]);
        }

        $response->assertStatus($responseCode);

        return $response;
    }

    /**
     * @param Model $model
     *
     * @return TestResponse
     */
    protected function doTestDestroyResponse(Model $model): TestResponse
    {
        $route = route(
            sprintf('v1.%s.destroy', static::resourceType()),
            [static::routeParamName() => $model]
        );

        return $this
            ->jsonApi()
            ->expects(static::resourceType())
            ->delete($route);
    }
}
