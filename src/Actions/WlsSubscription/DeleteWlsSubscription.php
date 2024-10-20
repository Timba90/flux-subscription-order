<?php

namespace WeblabStudio\Actions\WlsSubscription;

use FluxErp\Actions\FluxAction;
use WeblabStudio\Models\WlsSubscription;
use WeblabStudio\Rulesets\WlsSubscription\DeleteWlsSubscriptionRuleset;

class DeleteWlsSubscription extends FluxAction
{
    protected function boot(array $data): void
    {
        parent::boot($data);
        $this->rules = resolve_static(DeleteWlsSubscriptionRuleset::class, 'getRules');
    }

    public static function models(): array
    {
        return [DeleteWlsSubscription::class];
    }

    public function performAction(): mixed
    {
        return resolve_static(WlsSubscription::class, 'query')
            ->whereKey($this->data['id'])
            ->first()
            ->delete();
    }
}
