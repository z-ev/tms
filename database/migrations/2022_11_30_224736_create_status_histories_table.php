<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

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
        Schema::create('status_histories', function (Blueprint $table) {
            $table->uuid('id')
                ->primary()
                ->unique();
            $table->foreignUuid('order_id')
                ->nullable()
                ->references('id')->on('orders');
            $table->foreignId('user_id')
                ->nullable()
                ->references('id')->on('users');
            $table->uuid('merchant_id')->index()->nullable();
            $table->tinyInteger('status_id')->nullable();
            $table->text('description');
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
        Schema::dropIfExists('status_histories');
    }
};
