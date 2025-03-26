<?php

namespace WeblabStudio\Livewire\Order;

use FluxErp\Enums\OrderTypeEnum;
use FluxErp\Models\OrderType;
use FluxErp\Traits\Livewire\Actions;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use WeblabStudio\Invokables\ProcessWlsSubscriptions;
use WeblabStudio\Livewire\Forms\WlsSubscriptionForm;
use WeblabStudio\Models\WlsSubscription;

class WlsSubscriptionOrder extends Component
{
    use Actions;

    public array $executionIntervals = [];

    public array $executionTime = [];

    public ?int $order_id = null;

    public array $orderTypes = [];

    public WlsSubscriptionForm $wlsSubscriptionForm;

    public function mount(): void
    {
        $wlsSubscription = WlsSubscription::query()
            ->where('order_id', $this->order_id)
            ->first();

        if ($wlsSubscription) {
            $this->wlsSubscriptionForm->fill($wlsSubscription);
        } else {
            $this->wlsSubscriptionForm->order_id = $this->order_id;
            $this->wlsSubscriptionForm->execution_interval = 'monthly';
            $this->wlsSubscriptionForm->execution_time = 'last-of-month';
            $this->wlsSubscriptionForm->start_date = now()->format('Y-m-d');
        }

        $this->orderTypes = resolve_static(OrderType::class, 'query')
            ->where('order_type_enum', OrderTypeEnum::Order)
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        $this->executionIntervals = [
            ['name' => __('monthly'), 'value' => 'monthly'],
            ['name' => __('quarterly'), 'value' => 'quarterly'],
            ['name' => __('half-yearly'), 'value' => 'half-yearly'],
            ['name' => __('yearly'), 'value' => 'yearly'],
        ];

        $this->executionTime = [
            ['name' => __('first of month'), 'value' => 'first-of-month'],
            ['name' => __('last of month'), 'value' => 'last-of-month'],
        ];
    }

    public function render(): View
    {
        return view('weblab-subscription::livewire.order.wls-subscription-order');
    }

    public function delete(): void
    {
        $this->wlsSubscriptionForm->delete();
        $this->wlsSubscriptionForm->order_id = $this->order_id;
        $this->wlsSubscriptionForm->start_date = now()->format('Y-m-d');
        $this->wlsSubscriptionForm->execution_interval = 'monthly';
        $this->wlsSubscriptionForm->execution_time = 'last-of-month';

        $this->toast()->success(__('Subscription deleted successfully'))->send();
    }

    #[Renderless]
    public function save(): void
    {
        try {
            if ($this->wlsSubscriptionForm->next_action_date === null) {
                $this->wlsSubscriptionForm->next_action_date = WlsSubscription::firstActionDate(
                    $this->wlsSubscriptionForm->start_date,
                    $this->wlsSubscriptionForm->end_date,
                    $this->wlsSubscriptionForm->execution_time,
                    $this->wlsSubscriptionForm->execution_interval,
                    $this->wlsSubscriptionForm->is_active
                );
            } else {
                $subscription = WlsSubscription::query()
                    ->where('order_id', $this->order_id)
                    ->first();
                $this->wlsSubscriptionForm->next_action_date = $subscription->updateNextActionDate(
                    $this->wlsSubscriptionForm->next_action_date,
                    $this->wlsSubscriptionForm->execution_time,
                    $this->wlsSubscriptionForm->execution_interval
                );
            }
            $this->wlsSubscriptionForm->save();
        } catch (ValidationException $e) {
            exception_to_notifications($e, $this);

            return;
        }

        $this->toast()->success(__('Subscription saved successfully'))->send();
    }

    public function skip(): void
    {
        try {
            $wlsSubscription = resolve_static(WlsSubscription::class, 'query')
                ->where('order_id', $this->order_id)
                ->first();
            $wlsSubscription->makeNextActionDate();
            $this->wlsSubscriptionForm->next_action_date = $wlsSubscription->makeNextActionDate();
            $this->wlsSubscriptionForm->save();
        } catch (ValidationException $e) {
            exception_to_notifications($e, $this);

            return;
        }
        $this->notification()->success(__('Next action skipped successfully'));
    }

    public function test(): void
    {
        $a = new ProcessWlsSubscriptions();
        $a();
    }
}
