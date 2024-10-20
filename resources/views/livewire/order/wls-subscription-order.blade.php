<div>
    <x-card title="{{ __('Subscription') }}">
        <x-datetime-picker
            class="mb-1"
            label="{{ __('Run from') }}"
            value=""
            without-time
            wire:model="wlsSubscriptionForm.start_date"
        />
        <x-datetime-picker
            class="mb-1"
            label="{{ __('Run until') }}"
            without-time
            without-timezone
            wire:model="wlsSubscriptionForm.end_date"
        />
        <x-select
            class="mb-1"
            label="{{ __('Execution frequency') }}"
            :options="[
            ['name' => __('monthly'), 'value' => 'monthly'],
            ['name' => __('quarterly'), 'value' => 'quarterly'],
            ['name' => __('half-yearly'), 'value' => 'half-yearly'],
            ['name' => __('yearly'), 'value' => 'yearly']
            ]"
            option-label="name" option-value="value"
            wire:model="wlsSubscriptionForm.execution_interval"
        />
        <x-select
            class="mb-1"
            label="{{ __('Execution time') }}"
            :options="[
            ['name' => __('start date'), 'value' => 'start-date'],
            ['name' => __('first of month'), 'value' => 'first-of-month'],
            ['name' => __('last of month'), 'value' => 'last-of-month']
            ]"
            option-label="name" option-value="value"
            wire:model="wlsSubscriptionForm.execution_time"
        />
        <x-select
            class="mb-1"
            label="{{ __('Order type') }}"
            :options="$orderTypes"
            option-label="name"
            option-value="id"
            wire:model="wlsSubscriptionForm.order_type_id"
        />
        <div class="flex flex-row justify-between mt-4">
            <x-radio id="is_periodic" label="{{ __('Performance date') }}" wire:model="wlsSubscriptionForm.is_periodic" value="false" />
            <x-radio id="is_periodic" label="{{ __('Performance period') }}" wire:model="wlsSubscriptionForm.is_periodic" value="true" />
        </div>
        <div class="flex flex-row justify-between mt-4">
            <x-toggle id="is_active" wire:model="wlsSubscriptionForm.is_active" label="{{ __('Active') }}" name="toggle" />
            <x-toggle id="is_backdated" wire:model="wlsSubscriptionForm.is_backdated" label="{{ __('Backdated') }}" name="toggle" />
        </div>
        <x-button positive full class="mt-4" wire:click="save()">
                {{ $wlsSubscriptionForm->id ? __('Update') : __('Save') }}
        </x-button>
        <div class="text-xs mt-4 flex flex-row justify-between items-center">
            <div>{{ __('Next run') }}: <span x-text="$wire.wlsSubscriptionForm.next_action_date"></span></div>
            <div class="flex flex-row justify-end gap-1">
                <x-button xs primary icon="check" wire:click="test()" />
                <x-button xs secondary icon="chevron-double-right" wire:click="skip()" x-show="$wire.wlsSubscriptionForm.id"/>
                <x-button xs negative icon="trash" wire:click="delete()" x-show="$wire.wlsSubscriptionForm.id"/>
            </div>
        </div>
    </x-card>
</div>
