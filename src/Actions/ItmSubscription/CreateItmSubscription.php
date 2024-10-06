<?php

namespace WeblabStudio\Actions\ItmSubscription;

use WeblabStudio\Models\ItmSubscription;
use FluxErp\Actions\FluxAction;
use WeblabStudio\Rulesets\ItmSubscription\CreateItmSubscriptionRuleset;

class CreateItmSubscription extends FluxAction
{
    protected function boot(array $data): void
    {
        parent::boot($data);
        $this->rules = resolve_static(CreateItmSubscriptionRuleset::class, 'getRules');
    }

    public static function models(): array
    {
        return [ItmSubscription::class];
    }

    public function performAction(): ItmSubscription
    {
        $itmSubscription = app(ItmSubscription::class, ['attributes' => $this->data]);
        $itmSubscription->save();

        return $itmSubscription->fresh();
    }
}
