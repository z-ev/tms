<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends BaseModel
{
    use HasFactory;

    public const STATUS_PENDING = [
        'id'          => 1,
        'code'        => 'new',
        'title'       => 'Новый заказ',
        'variability' => true,
    ];

    public const STATUS_START_PICKED = [
        'id'          => 2,
        'title'       => 'Собирается',
        'code'        => 'start_picked',
        'variability' => true,
    ];

    public const STATUS_READY_PICKED = [
        'id'          => 3,
        'title'       => 'Готов к отгрузке',
        'code'        => 'ready_picked',
        'variability' => true,
    ];

    public const STATUS_TRANSF_ON_DOCK = [
        'id'          => 4,
        'title'       => 'Отправлен на склад',
        'code'        => 'transf_on_dock',
        'variability' => true,
    ];

    public const STATUS_ACCEPTED_ON_DOCK = [
        'id'          => 5,
        'title'       => 'Принят на складе',
        'code'        => 'accepted_on_dock',
        'variability' => true,
    ];

    public const STATUS_COMPLETE = [
        'id'          => 6,
        'title'       => 'Передан покупателю',
        'code'        => 'complete',
        'variability' => false,
    ];

    public const STATUS_CANCELED = [
        'id'          => 7,
        'title'       => 'Отмена',
        'code'        => 'canceled',
        'variability' => true,
    ];

    public const STATUS_REFUND = [
        'id'          => 8,
        'title'       => 'Возврат',
        'code'        => 'refund',
        'variability' => true,
    ];

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_START_PICKED,
        self::STATUS_READY_PICKED,
        self::STATUS_TRANSF_ON_DOCK,
        self::STATUS_ACCEPTED_ON_DOCK,
        self::STATUS_COMPLETE,
        self::STATUS_CANCELED,
        self::STATUS_REFUND,
    ];

    public const VIEW_ORDER_CREATED = 'order.created';

    protected $fillable = [
        'number',
        'address',
        'description',
        'status_id',
        'weight',
        'amount',
        'amount_without_vat',
        'currency',
        'items',
        'vat_amount',
        'vat_percent',
        'refunded_amount',
        'refunded_amount_without_vat',
        'refunded_total_vat_amount',
        'refunded_total_qty',
        'delivered_at',
        'refunded_at',
    ];

    /**
     * @return HasOne<User>
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne<Merchant>
     */
    public function merchant(): HasOne
    {
        return $this->hasOne(Merchant::class, 'merchant_id', 'user_id');
    }
}
