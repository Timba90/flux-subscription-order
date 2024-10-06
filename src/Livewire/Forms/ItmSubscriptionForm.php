<?php

namespace WeblabStudio\Livewire\Forms;

use FluxErp\Livewire\Forms\FluxForm;
use Livewire\Attributes\Locked;
use WeblabStudio\Actions\ItmSubscription\CreateItmSubscription;
use WeblabStudio\Actions\ItmSubscription\DeleteItmSubscription;
use WeblabStudio\Actions\ItmSubscription\UpdateItmSubscription;

class ItmSubscriptionForm extends FluxForm
{
    #[Locked]
    public ?int $id = null;

    public ?int $order_id = null;

    public ?string $end_date = null;

    public ?string $start_date = null;

    public ?string $execution_interval = null;

    public ?string $execution_time = null;

    public bool $is_periodic = false;

    public bool $is_active = false;

    public bool $is_backdated = false;

    public ?string $last_action_date = null;

    public ?string $next_action_date = null;

    public function getActions(): array
    {
        return [
            'create' => CreateItmSubscription::class,
            'update' => UpdateItmSubscription::class,
            'delete' => DeleteItmSubscription::class,
        ];
    }
}
