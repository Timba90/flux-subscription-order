<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wls_subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('execution_interval');
            $table->string('execution_time');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_periodic')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_backdated')->default(false);
            $table->date('last_action_date')->nullable();
            $table->date('next_action_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itm_subscriptions');
    }
};
