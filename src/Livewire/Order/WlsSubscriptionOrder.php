<?php

namespace WeblabStudio\Livewire\Order;

use FluxErp\Enums\OrderTypeEnum;
use FluxErp\Models\OrderType;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use WeblabStudio\Invokables\ProcessWlsSubscriptions;
use WeblabStudio\Livewire\Forms\WlsSubscriptionForm;
use WeblabStudio\Models\WlsSubscription;
use WireUi\Traits\Actions;

class WlsSubscriptionOrder extends Component
{
    use Actions;

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
            $this->wlsSubscriptionForm->start_date = now()->format('Y-m-d');
        }

        $this->orderTypes = resolve_static(OrderType::class, 'query')
            ->where('order_type_enum', OrderTypeEnum::Order)
            ->select(['id', 'name'])
            ->get()
            ->toArray();
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
                $this->wlsSubscriptionForm->next_action_date = $subscription->updateNextActionDate();
            }
            $this->wlsSubscriptionForm->save();

        } catch (ValidationException $e) {
            exception_to_notifications($e, $this);

            return;
        }

        $this->notification()->success(__('Subscription saved successfully'));
    }

    public function delete(): void
    {
        $this->wlsSubscriptionForm->delete();
        $this->wlsSubscriptionForm->order_id = $this->order_id;
        $this->wlsSubscriptionForm->start_date = now()->format('Y-m-d');

        $this->notification()->success(__('Subscription deleted successfully'));
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

    public function render(): View
    {
        return view('weblab-subscription::livewire.order.wls-subscription-order');
    }
}
