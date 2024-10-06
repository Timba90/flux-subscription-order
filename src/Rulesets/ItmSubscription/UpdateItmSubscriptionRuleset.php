<?php

namespace WeblabStudio\Rulesets\ItmSubscription;

use WeblabStudio\Models\ItmSubscription;
use FluxErp\Models\Order;
use FluxErp\Rules\ModelExists;
use FluxErp\Rulesets\FluxRuleset;

class UpdateItmSubscriptionRuleset extends FluxRuleset
{
    protected static ?string $model = ItmSubscription::class;

    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                app(ModelExists::class, ['model' => ItmSubscription::class]),
            ],
            'order_id' => [
                'required',
                'integer',
                app(ModelExists::class, ['model' => Order::class]),
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
                'string',
            ],
            'is_periodic' => [
                'boolean',
            ],
            'is_active' => [
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
