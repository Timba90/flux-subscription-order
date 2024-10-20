<?php

namespace WeblabStudio\Rulesets\WlsSubscription;

use FluxErp\Rules\ModelExists;
use FluxErp\Rulesets\FluxRuleset;
use WeblabStudio\Models\WlsSubscription;

class DeleteWlsSubscriptionRuleset extends FluxRuleset
{
    protected static ?string $model = WlsSubscription::class;

    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                app(ModelExists::class, ['model' => WlsSubscription::class]),
            ],
        ];
    }
}
