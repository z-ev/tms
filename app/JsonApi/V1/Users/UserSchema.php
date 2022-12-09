<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Users;

use App\Models\User;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Schema;

class UserSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = User::class;

    /**
     * Get the resource fields.
     *
     * @return iterable<mixed>
     */
    public function fields(): iterable
    {
        return [
            ID::make(),
            Str::make('name')->sortable(),
            Str::make('email')->sortable(),
            Str::make('merchantId')->sortable(),
            Number::make('statusId')->sortable(),
            ArrayList::make('roles'),
            ArrayList::make('settings'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
        ];
    }
}
