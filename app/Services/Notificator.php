<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Notifications\Telegram;
use Illuminate\Support\Facades\Notification;

class Notificator
{
    public const VIEW_POSTFIX_MAIL     = '.mail';
    public const VIEW_POSTFIX_TELEGRAM = '.telegram';

    /**
     * @param Order  $order
     * @param string $view
     *
     * @return void
     */
    public function sendNotifications(Order $order, string $view): void
    {
        if ((bool) env('NOTIFIER_TELEGRAM', false)) {
            $this->sendToTelegram(
                [env('NOTIFIER_TELEGRAM_CHAT')],
                $order,
                $view . self::VIEW_POSTFIX_TELEGRAM
            );
        }
    }

    /**
     * @param array<string> $list
     * @param Order         $order
     * @param string        $view
     *
     * @return void
     */
    public function sendToTelegram(array $list, Order $order, string $view): void
    {
        Notification::send($list, new Telegram($order, $view));
    }
}
