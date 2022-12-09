<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Resources\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait FiltersTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public static function model(): string;

    abstract public static function resourceType(): string;

    abstract public function testFilters(): void;

    /**
     * @param array<string,mixed> $filters
     * @param Model               $expected
     * @param string              $role
     *
     * @return void
     */
    protected function doTestFilters(Model $expected, array $filters, string $role = User::ROLE_CLIENT): void
    {
        $this->actAs([$role]);

        static::model()::factory(10)->create();

        $response = $this
            ->jsonApi()
            ->expects(static::resourceType())
            ->filter($filters)
            ->get(route(sprintf('v1.%s.index', static::resourceType())));

        $response->assertFetchedMany([$expected]);
    }
}
