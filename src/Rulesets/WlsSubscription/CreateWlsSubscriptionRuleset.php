<?php

namespace WeblabStudio\Rulesets\WlsSubscription;

use FluxErp\Models\Order;
use FluxErp\Models\OrderType;
use FluxErp\Rules\ModelExists;
use FluxErp\Rulesets\FluxRuleset;
use WeblabStudio\Models\WlsSubscription;

class CreateWlsSubscriptionRuleset extends FluxRuleset
{
    protected static ?string $model = WlsSubscription::class;

    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                app(ModelExists::class, ['model' => Order::class]),
            ],
            'order_type_id' => [
                'required',
                'integer',
                app(ModelExists::class, ['model' => OrderType::class]),
            ],
            'end_date' => [
                'nullable',
                'date',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'execution_interval' => [
                'required',
                'string',
            ],
            'execution_time' => [
                'required',
                'string',
            ],
            'is_periodic' => [
                'boolean',
            ],
            'is_active' => [
                'boolean',
            ],
            'is_automatic' => [
                'boolean',
            ],
            'is_backdated' => [
                'boolean',
            ],
            'last_action_date' => [
                'nullable',
                'date',
            ],
            'next_action_date' => [
                'nullable',
                'date',
            ],
        ];
    }
}
