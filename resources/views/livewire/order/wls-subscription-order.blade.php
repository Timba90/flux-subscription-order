<div>
    <x-card title="ITM Subscription">
        <x-datetime-picker
            class="mb-1"
            label="Ausführen ab"
            value=""
            without-time
            wire:model="wlsSubscriptionForm.start_date"
        />
        <x-datetime-picker
            class="mb-1"
            label="Ausführen bis"
            without-time
            without-timezone
            wire:model="wlsSubscriptionForm.end_date"
        />
        <x-select
            class="mb-1"
            label="Ausführungsrhythmus"
            :options="['Monatlich', 'Quartalsweise', 'Halbjährlich', 'Jährlich']"
            wire:model="wlsSubscriptionForm.execution_interval"
        />
        <x-select
            class="mb-1"
            label="Ausführungszeitpunkt"
            :options="['Am Tag', 'Anfang des Monats', 'Ende des Monats']"
            wire:model="wlsSubscriptionForm.execution_time"
        />
        <x-select
            class="mb-1"
            label="Auftragsart"
            :options="$orderTypes"
            option-label="name"
            option-value="id"
            wire:model="wlsSubscriptionForm.order_type_id"
        />
        <div class="flex flex-row justify-between mt-4">
            <x-radio id="is_periodic" label="Leistungsdatum" wire:model="wlsSubscriptionForm.is_periodic" value="false" />
            <x-radio id="is_periodic" label="Leistungszeitraum" wire:model="wlsSubscriptionForm.is_periodic" value="true" />
        </div>
        <div class="flex flex-row justify-between mt-4">
            <x-toggle id="is_active" wire:model="wlsSubscriptionForm.is_active" label="Aktiv" name="toggle" />
            <x-toggle id="is_backdated" wire:model="wlsSubscriptionForm.is_backdated" label="Rückwirkend" name="toggle" />
        </div>
        <x-button positive full class="mt-4" wire:click="save()">
                {{ $wlsSubscriptionForm->id ? __('Update') : __('Save') }}
        </x-button>
        <div class="text-xs mt-4 flex flex-row justify-between items-center">
            <div>Nächste Ausführung: <span x-text="$wire.wlsSubscriptionForm.next_action_date"></span></div>
            <div class="flex flex-row justify-end gap-1">
                <x-button xs primary icon="check" wire:click="test()" />
                <x-button xs secondary icon="chevron-double-right" wire:click="skip()" x-show="$wire.wlsSubscriptionForm.id"/>
                <x-button xs negative icon="trash" wire:click="delete()" x-show="$wire.wlsSubscriptionForm.id"/>
            </div>
        </div>
    </x-card>
</div>
