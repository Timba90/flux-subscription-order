<?php

namespace WeblabStudio\Actions\ItmSubscription;

use WeblabStudio\Models\ItmSubscription;
use FluxErp\Actions\FluxAction;
use WeblabStudio\Rulesets\ItmSubscription\UpdateItmSubscriptionRuleset;

class UpdateItmSubscription extends FluxAction
{
    protected function boot(array $data): void
    {
        parent::boot($data);
        $this->rules = resolve_static(UpdateItmSubscriptionRuleset::class, 'getRules');
    }

    public static function models(): array
    {
        return [ItmSubscription::class];
    }

    public function performAction(): mixed
    {
        $itmSubscription = resolve_static(ItmSubscription::class, 'query')
            ->whereKey($this->data['id'])
            ->first();

        $itmSubscription->fill($this->data);
        $itmSubscription->save();

        return $itmSubscription->withoutRelations()->fresh();
    }
}
