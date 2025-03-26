<?php

namespace WeblabStudio;

use FluxErp\Facades\Repeatable;
use FluxErp\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use WeblabStudio\Invokables\ProcessWlsSubscriptions;
use WeblabStudio\Livewire\Order\WlsSubscriptionOrder;
use WeblabStudio\Models\WlsSubscription;

class SubscriptionOrderServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'weblab-subscription');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');

        Livewire::component('order.itm-subscription-order', WlsSubscriptionOrder::class);

        Order::resolveRelationUsing(
            'wlsSubscription',
            function (Order $order): HasOne {
                return $order->hasOne(WlsSubscription::class);
            }
        );

        Repeatable::register('itm-subscription', ProcessWlsSubscriptions::class);
    }
}
