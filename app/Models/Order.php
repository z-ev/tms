<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Core\Exceptions\JsonApiException;
use Symfony\Component\HttpFoundation\Response;

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
    public const VIEW_ORDER_UPDATED = 'order.updated';

    public const MERCHANT_ID_COLUMN = 'merchant_id';

    protected $fillable = [
        'number',
        'address',
        'description',
        self::MERCHANT_ID_COLUMN,
        'status_id',
        'user_id',
        'weight',
        'total_amount',
        'total_amount_without_vat',
        'total_vat_amount',
        'currency',
        'items',
        'refunded_amount',
        'refunded_amount_without_vat',
        'refunded_total_vat_amount',
        'refunded_total_qty',
        'delivered_at',
        'refunded_at',
    ];

    protected $casts = [
        'items' => 'array',
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
        return $this->hasOne(Merchant::class, 'id', 'merchant_id');
    }

    /**
     * @return array<string, mixed>
     */
    public function getStatus(): array
    {
        return self::STATUSES[array_search($this->status_id, array_column(self::STATUSES, 'id'), true)];
    }

    /**
     * @param int    $statusId
     * @param string $reason
     *
     * @throws JsonApiException
     */
    public function updateStatus(int $statusId, string $reason = ''): void
    {
        if ($this->status_id === $statusId) {
            throw JsonApiException::error([
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'detail' => __('toms.orders.status.already.set'),
            ]);
        }

        $user = Auth::user();

        $status = $this->getStatus();

        if ($status == []) {
            throw JsonApiException::error([
                'status' => Response::HTTP_NOT_MODIFIED,
                'detail' => __('toms.orders.defunct.status'),
            ]);
        }

        if (
            $status['variability'] == false
            && !$user->isAdministrator()
        ) {
            throw JsonApiException::error([
                'status' => Response::HTTP_NOT_MODIFIED,
                'detail' => __('toms.orders.status.not.variable'),
            ]);
        }

        $this->status_id = $status['id'];
        $this->save();

        $statusHistory = new StatusHistory([
            'order_id'    => $this->id,
            'status_id'   => $status['id'],
            'merchant_id' => $this->merchant_id,
            'description' => (bool) $reason ? $status['title'] . ': ' . $reason : $status['title'],
            'user_id'     => $user->id ?? null,
        ]);

        $statusHistory->save();
    }
}
