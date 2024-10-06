<?php

namespace WeblabStudio\Actions\ItmSubscription;

use FluxErp\Actions\FluxAction;

class DeleteItmSubscription extends FluxAction
{
    protected function boot(array $data): void
    {
        parent::boot($data);
        $this->rules = resolve_static(DeleteItmSubscriptionRuleset::class, 'getRules');
    }

    public static function models(): array
    {
        return [DeleteItmSubscription::class];
    }

    public function performAction(): mixed
    {
        return resolve_static(DeleteItmSubscription::class, 'query')
            ->whereKey($this->data['id'])
            ->first()
            ->delete();
    }
}
