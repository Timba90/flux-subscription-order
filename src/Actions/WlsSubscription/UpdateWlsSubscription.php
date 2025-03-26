<?php

namespace WeblabStudio\Actions\WlsSubscription;

use FluxErp\Actions\FluxAction;
use WeblabStudio\Models\WlsSubscription;
use WeblabStudio\Rulesets\WlsSubscription\UpdateWlsSubscriptionRuleset;

class UpdateWlsSubscription extends FluxAction
{
    public static function models(): array
    {
        return [WlsSubscription::class];
    }

    protected function boot(array $data): void
    {
        parent::boot($data);
        $this->rules = resolve_static(UpdateWlsSubscriptionRuleset::class, 'getRules');
    }

    public function performAction(): mixed
    {
        $wlsSubscription = resolve_static(WlsSubscription::class, 'query')
            ->whereKey($this->data['id'])
            ->first();

        $wlsSubscription->fill($this->data);
        $wlsSubscription->save();

        return $wlsSubscription->withoutRelations()->fresh();
    }
}
