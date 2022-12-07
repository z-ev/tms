<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')
                ->primary()
                ->unique();
            $table->string('number')->index();
            $table->uuid('user_id')->index();

            $table->uuid('merchant_id')->index()->nullable();

            $table->string('currency')
                ->nullable()
                ->default('₽');

            $table->json('items')->nullable();

            $table->tinyInteger('status_id')->default(Order::STATUS_PENDING['id']);

            $table->integer('total_amount')->nullable();
            $table->integer('total_amount_without_vat')->nullable();
            $table->integer('total_vat_amount')->nullable();

            $table->decimal('weight')->nullable();

            $table->dateTime('delivered_at')->index()->nullable();
            $table->dateTime('refunded_at')->index()->nullable();

            $table->integer('refunded_amount')->nullable()->default(0);
            $table->integer('refunded_amount_without_vat')->nullable()->default(0);
            $table->integer('refunded_total_vat_amount')->nullable()->default(0);
            $table->integer('refunded_total_qty')->nullable()->default(0);

            $table->string('address')->nullable();
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
