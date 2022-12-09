<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Resources\Traits;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Model;
use LaravelJsonApi\Testing\TestResponse;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait IndexTrait
{
    /**
     * @return class-string<Model>
     */
    abstract public static function model(): string;

    abstract public static function resourceType(): string;

    abstract public function testIndex(): void;

    protected function doTestAdminIndex(): void
    {
        $this->actAs([User::ROLE_ADMIN]);

        static::model()::factory()->create(['merchant_id' => DatabaseSeeder::MOCK_MERCHANT_ID]);

        $expectedData = static::model()::all();

        $response = $this->doTestResponse();

        $response->assertFetchedMany($expectedData);
    }

    /**
     * @return TestResponse
     */
    protected function doTestResponse(): TestResponse
    {
        return $this
            ->jsonApi()
            ->expects(static::resourceType())
            ->page([
                'number' => 1,
                'size'   => 100000,
            ])
            ->get(route(sprintf('v1.%s.index', static::resourceType())));
    }
}
