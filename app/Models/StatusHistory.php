<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StatusHistory extends Model
{
    use HasFactory;

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
     * @return HasOne<Order>
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'merchant_id');
    }
}
