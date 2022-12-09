<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests;

use App\Models\Merchant;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesJsonApiRequests;

    /**
     * @param string $method
     * @param string $model
     * @param array  $params
     *
     * @return Model
     */
    protected function prepareMerchantEntity(string $method, string $model, array $params = []): Model
    {
        /** @var User $user */
        $merchantId = Auth::user()->merchant_id ?? DatabaseSeeder::MOCK_MERCHANT_ID;

        return $this->prepareEntity($method, $model, $merchantId, $params);
    }

    /**
     * @param string $model
     * @param array  $params
     * @param string $method
     *
     * @return Model
     */
    protected function prepareAlienEntity(string $method, string $model, array $params = []): Model
    {
        $merchant = Merchant::factory()->create();

        return $this->prepareEntity($method, $model, $merchant->id, $params);
    }

    /**
     * @param string $method
     * @param string $model
     * @param string $merchantId
     * @param array  $params
     *
     * @return Model
     */
    protected function prepareEntity(
        string $method,
        string $model,
        string $merchantId,
        array $params = []
    ): Model {
        return $model::factory()->{$method}(array_merge([
            constant("{$model}::MERCHANT_ID_COLUMN") => $merchantId,
        ], $params));
    }
}
