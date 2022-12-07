<?php

declare(strict_types=1);
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

namespace App\JsonApi\V1;

use App\JsonApi\V1\Orders\OrderSchema;
use App\JsonApi\V1\Users\UserSchema;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{
    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            OrderSchema::class,
            UserSchema::class,
        ];
    }
}
