<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;
use App\Services\Notificator;

class OrderObserver
{
    protected Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    /**
     * Handle the Order "created" event.
     *
     * @param Order $order
     *
     * @return void
     */
    public function created(Order $order): void
    {
        $this->notificator->sendNotifications($order, Order::VIEW_ORDER_CREATED);
    }
}
