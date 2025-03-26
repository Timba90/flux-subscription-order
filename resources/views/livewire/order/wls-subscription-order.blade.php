<div>
    <x-card title="{{ __('Subscription') }}">
        <x-date
            class="mb-1"
            label="{{ __('Run from') }}"
            value=""
            without-time
            wire:model="wlsSubscriptionForm.start_date"
        />
        <x-date
            class="mb-1"
            label="{{ __('Run until') }}"
            without-time
            without-timezone
            wire:model="wlsSubscriptionForm.end_date"
        />
        <x-select.native
            class="mb-1"
            label="{{ __('Execution frequency') }}"
            :options="$executionIntervals"
            select="label:name|value:value"
            wire:model="wlsSubscriptionForm.execution_interval"
        />
        <x-select.native
            class="mb-1"
            label="{{ __('Execution time') }}"
            :options="$executionTime"
            select="label:name|value:value"
            wire:model="wlsSubscriptionForm.execution_time"
        />
        <x-select.styled
            class="mb-1"
            label="{{ __('Order type') }}"
            :options="$orderTypes"
            select="label:name|value:id"
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
        <div class="flex flex-row justify-between mt-4 gap-2">
            <x-button color="primary" class="w-1/2" wire:click="save()">
                    {{ $wlsSubscriptionForm->id ? __('Update') : __('Save') }}
            </x-button>
            <x-button color="red" class="w-1/2"  wire:click="delete()" x-show="$wire.wlsSubscriptionForm.id">
                {{ __('Delete') }}
            </x-button>
        </div>
        <div class="text-xs mt-4 flex flex-row justify-between items-center">
            <div>{{ __('Next run') }}: <span x-text="$wire.wlsSubscriptionForm.next_action_date"></span></div>
            <div class="flex flex-row justify-end gap-1">

            </div>
        </div>
    </x-card>
</div>
