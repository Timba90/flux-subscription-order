<?php

namespace WeblabStudio\Livewire\Order;

use App\Models\ItmSubscription;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use WeblabStudio\Livewire\Forms\ItmSubscriptionForm;
use WireUi\Traits\Actions;

class ItmSubscriptionOrder extends Component
{
    use Actions;

    public ?int $order_id = null;

    public ItmSubscriptionForm $itmSubscriptionForm;

    public function mount(): void
    {
        $itmSubscription = ItmSubscription::query()
            ->where('order_id', $this->order_id)
            ->first();

        if ($itmSubscription) {
            $this->itmSubscriptionForm->fill($itmSubscription);
        } else {
            $this->itmSubscriptionForm->order_id = $this->order_id;
            $this->itmSubscriptionForm->start_date = now()->format('Y-m-d');
        }
    }

    #[Renderless]
    public function save(): void
    {
        try {
            $this->itmSubscriptionForm->save();
        } catch (ValidationException $e) {
            exception_to_notifications($e, $this);

            return;
        }

        $this->notification()->success(__('Subscription saved successfully'));
    }

    public function render(): View
    {
        return view('weblab-subscription::livewire.order.itm-subscription-order');
    }
}
