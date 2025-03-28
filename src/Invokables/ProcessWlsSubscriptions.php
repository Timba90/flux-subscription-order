<?php

namespace WeblabStudio\Invokables;

use Cron\CronExpression;
use FluxErp\Actions\Order\ReplicateOrder;
use FluxErp\Console\Scheduling\Repeatable;
use FluxErp\Enums\OrderTypeEnum;
use FluxErp\Models\Order;
use FluxErp\Models\OrderType;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Traits\LogsActivity;
use Throwable;
use WeblabStudio\Actions\WlsSubscription\UpdateWlsSubscription;
use WeblabStudio\Models\WlsSubscription;

class ProcessWlsSubscriptions implements Repeatable
{
    public function __invoke(): void
    {
        $today = now()->format('Y-m-d');
        $subscriptions = resolve_static(WlsSubscription::class, 'query')
            ->where('next_action_date', $today)
            ->get();
        //        $orderType = resolve_static(OrderType::class, 'query')
        //            ->where('order_type_enum', OrderTypeEnum::Order)
        //            ->first();

        // Test in tinker
        // $a = new WeblabStudio\Invokables\ProcessWlsSubscriptions; $a();
        //        $a = new ProcessSubscriptionOrder;
        foreach ($subscriptions as $subscription) {
            $orderCreated = $this->recreateOrder(
                $subscription->order_id,
                $subscription->order_type_id,
                $subscription->execution_interval,
                $subscription->is_backdated,
                $subscription->is_periodic
            );

            if ($orderCreated) {
                $subscription->next_action_date = $subscription->makeNextActionDate();
                $subscription->last_action_date = $today;
                UpdateWlsSubscription::make($subscription)
                    ->validate()
                    ->execute();
            }
        }
    }

    public static function defaultCron(): ?CronExpression
    {
        return null;
    }

    public static function description(): ?string
    {
        return 'Process WLS subscriptions';
    }

    public static function isRepeatable(): bool
    {
        return true;
    }

    public static function name(): string
    {
        return class_basename(static::class);
    }

    public static function parameters(): array
    {
        return [];
    }

    public function recreateOrder(
        int|string $orderId,
        int|string $orderTypeId,
        ?string $execution_interval,
        bool $is_backdated,
        bool $is_periodic,
    ): bool {
        $order = resolve_static(Order::class, 'query')
            ->whereKey($orderId)
            ->first();

        $orderType = resolve_static(OrderType::class, 'query')
            ->whereKey($orderTypeId)
            ->first();

        if (! $order || ! $orderType) {
            return false;
        }

        if (! in_array(
            $order->orderType->order_type_enum,
            [OrderTypeEnum::Subscription, OrderTypeEnum::PurchaseSubscription]
        )) {
            return false;
        }

        // Update parent_id and performance period
        $order->parent_id = $order->id;
        $order->order_type_id = $orderType->id;

        // Set performance period
        if ($is_periodic) {
            if ($is_backdated) {
                $order->system_delivery_date_end = now();
                if ($execution_interval === 'monthly') {
                    $order->system_delivery_date = $order->system_delivery_date_end->subMonth();
                } elseif ($execution_interval === 'quarterly') {
                    $order->system_delivery_date = $order->system_delivery_date_end->subMonth(3);
                } elseif ($execution_interval === 'half-yearly') {
                    $order->system_delivery_date = $order->system_delivery_date_end->subMonth(6);
                } elseif ($execution_interval === 'yearly') {
                    $order->system_delivery_date = $order->system_delivery_date_end->subYear();
                }
            } else {
                $order->system_delivery_date = now();
                if ($execution_interval === 'monthly') {
                    $order->system_delivery_date_end = $order->system_delivery_date->addMonth();
                } elseif ($execution_interval === 'quarterly') {
                    $order->system_delivery_date_end = $order->system_delivery_date->addMonth(3);
                } elseif ($execution_interval === 'half-yearly') {
                    $order->system_delivery_date_end = $order->system_delivery_date->addMonth(6);
                } elseif ($execution_interval === 'yearly') {
                    $order->system_delivery_date_end = $order->system_delivery_date->addYear();
                }
            }
        } else {
            $order->system_delivery_date = now();
        }

        try {
            ReplicateOrder::make($order)->validate()->execute();

            return true;
        } catch (Throwable $e) {
            $activity = activity()
                ->event(static::class)
                ->byAnonymous();

            if (in_array(LogsActivity::class, class_uses_recursive($order))) {
                $activity->performedOn($order);
            }

            if ($e instanceof ValidationException) {
                $activity->withProperties(['data' => $order, 'errors' => $e->errors()]);
            }

            $activity->log(class_basename($e));

            return false;
        }
    }
}
