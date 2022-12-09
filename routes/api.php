<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

use App\Http\Controllers\Api\V1\Orders\UpdateStatusAction;
use App\Http\Controllers\Api\V1\Users\SignInAction;
use App\Http\Controllers\Api\V1\Users\SignUpAction;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use \LaravelJsonApi\Laravel\Routing\Relationships;
use Illuminate\Support\Facades\Route;
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

Route::post('/v1/users/actions/signin', [SignInAction::class, '__invoke'])->name('v1.users.signIn');
Route::post('/v1/users/actions/signup', [SignUpAction::class, '__invoke'])->name('v1.users.signUp');

JsonApiRoute::server('v1')
    ->prefix('v1')
    ->middleware('auth:sanctum')
    ->resources(function (ResourceRegistrar $server) {
        $server->resource('orders', JsonApiController::class)
            ->relationships(function (Relationships $relations) {
                $relations->hasOne('user');
                $relations->hasOne('merchant');
                $relations->hasMany('statusHistories');
            });
        $server->resource('orders', UpdateStatusAction::class)
            ->actions('actions', function ($actions) {
                $actions->withId()->post('update-status', '__invoke')->name('updateStatus');
            });

        $server->resource('users', JsonApiController::class);
});
