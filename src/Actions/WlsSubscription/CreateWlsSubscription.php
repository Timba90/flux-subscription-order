<?php

namespace WeblabStudio\Actions\WlsSubscription;

use FluxErp\Actions\FluxAction;
use WeblabStudio\Models\WlsSubscription;
use WeblabStudio\Rulesets\WlsSubscription\CreateWlsSubscriptionRuleset;

class CreateWlsSubscription extends FluxAction
{
    protected function boot(array $data): void
    {
        parent::boot($data);
        $this->rules = resolve_static(CreateWlsSubscriptionRuleset::class, 'getRules');
    }

    public static function models(): array
    {
        return [WlsSubscription::class];
    }

    public function performAction(): WlsSubscription
    {
        $wlsSubscription = app(WlsSubscription::class, ['attributes' => $this->data]);
        $wlsSubscription->save();

        return $wlsSubscription->fresh();
    }
}
