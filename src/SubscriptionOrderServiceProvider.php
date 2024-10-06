<?php

namespace WeblabStudio;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use WeblabStudio\Livewire\Order\ItmSubscriptionOrder;

class SubscriptionOrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'weblab-subscription');

        Livewire::component('order.itm-subscription-order', ItmSubscriptionOrder::class);
    }

    public function boot(): void
    {

    }
}
