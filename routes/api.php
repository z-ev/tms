<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

use App\Http\Controllers\Api\V1\Users\SignInAction;
use App\Http\Controllers\Api\V1\Users\SignUpAction;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use \LaravelJsonApi\Laravel\Routing\Relationships;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
JsonApiRoute::server('v1')
    ->prefix('v1')
    ->resources(function (ResourceRegistrar $server) {
        $server->resource('orders', JsonApiController::class)
            ->relationships(function (Relationships $relations) {
                $relations->hasOne('user');
                $relations->hasOne('merchant');
                $relations->hasMany('statusHistories');
            });

        $server->resource('users', JsonApiController::class);
        $server->resource('users', SignUpAction::class)
            ->actions('actions', function ($actions) {
                $actions->post('signup', '__invoke')->name('signUp');
            });
        $server->resource('users', SignInAction::class)
            ->actions('actions', function ($actions) {
                $actions->post('signin', '__invoke')->name('signIn');
            });
});
