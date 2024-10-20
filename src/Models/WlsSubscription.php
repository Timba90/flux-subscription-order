<?php

namespace WeblabStudio\Models;

use Carbon\Carbon;
use FluxErp\Models\Order;
use FluxErp\Models\OrderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WlsSubscription extends Model
{
    protected $guarded = [
        'id',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function order_type(): HasOne
    {
        return $this->hasOne(OrderType::class);
    }

    public static function firstActionDate(
        ?string $startDate,
        ?string $endDate,
        ?string $executionTime,
        ?string $executionInterval,
        bool $isActive
    ): ?string {
        $startDate = new Carbon($startDate);
        $firstActionDate = new Carbon($startDate);

        // Set the first action date to the start of the month
        if ($executionTime === 'first-of-month') {
            $firstActionDate->startOfMonth();
        }
        // Set the first action date to the end of the month
        if ($executionTime === 'last-of-month') {
            $firstActionDate->endOfMonth();
        }

        // Adds the execution interval to the first action date if it is before the start date
        if ($firstActionDate < $startDate) {
            if ($executionInterval === 'monthly') {
                $firstActionDate->addMonth();
            } elseif ($executionInterval === 'quarterly') {
                $firstActionDate->addMonth(3);
            } elseif ($executionInterval === 'half-yearly') {
                $firstActionDate->addMonth(6);
            } elseif ($executionInterval === 'yearly') {
                $firstActionDate->addYear();
            }
        }

        // Stopps if the subscription is not active
        if (! $isActive) {
            return null;
        }

        // Stopps if the first action date is after the end date
        if ($endDate !== null) {
            $endDate = new Carbon($endDate);
            if ($firstActionDate >= $endDate->addDay()) {
                return null;
            }
        }

        return $firstActionDate;
    }

    public function makeNextActionDate(): ?string
    {
        $nextActionDate = new Carbon($this->next_action_date);
        if ($this->execution_interval === 'monthly') {
            $nextActionDate->addMonth();
        } elseif ($this->execution_interval === 'quarterly') {
            $nextActionDate->addMonth(3);
        } elseif ($this->execution_interval === 'half-yearly') {
            $nextActionDate->addMonth(6);
        } elseif ($this->execution_interval === 'yearly') {
            $nextActionDate->addYear();
        }

        return $nextActionDate;
    }

    public function updateNextActionDate(): string
    {
        $nextActionDate = new Carbon($this->next_action_date);

        if ($this->execution_time === 'Anfang des Monats') {
            $nextActionDate->startOfMonth();
        }
        // Set the first action date to the end of the month
        if ($this->execution_time === 'Ende des Monats') {
            $nextActionDate->endOfMonth();
        }

        if ($nextActionDate < $this->startDate) {
            if ($this->execution_interval === 'monthly') {
                $nextActionDate->addMonth();
            } elseif ($this->execution_interval === 'quarterly') {
                $nextActionDate->addMonth(3);
            } elseif ($this->execution_interval === 'half-yearly') {
                $nextActionDate->addMonth(6);
            } elseif ($this->execution_interval === 'yearly') {
                $nextActionDate->addYear();
            }
        }

        return $nextActionDate;
    }
}
