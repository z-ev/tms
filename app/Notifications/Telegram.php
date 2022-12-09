<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class Telegram extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param Order  $order
     * @param string $view
     */
    public function __construct(public Order $order, protected string $view)
    {
    }

    /**
     * @param string $notifiable
     *
     * @return string[]
     */
    public function via(string $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * @param string $notifiable
     *
     * @return TelegramMessage
     */
    public function toTelegram(string $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable)
            ->options(
                [
                    'parse_mode'               => 'html',
                    'mime_type'                => 'html',
                    'disable_web_page_preview' => true,
                ]
            )->view($this->view, ['order' => $this->order]);
    }
}
