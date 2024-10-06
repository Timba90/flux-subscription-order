<div>
    <x-card title="ITM Subscription">
        <x-datetime-picker
            class="mb-1"
            label="Ausführen ab"
            value=""
            without-time
            wire:model="itmSubscriptionForm.start_date"
        />
        <x-datetime-picker
            class="mb-1"
            label="Ausführen bis"
            without-time
            without-timezone
            wire:model="itmSubscriptionForm.end_date"
        />
        <x-select
            class="mb-1"
            label="Ausführungsrhythmus"
            :options="['Monatlich', 'Quartalsweise', 'Halbjährlich', 'Jährlich']"
            wire:model="itmSubscriptionForm.execution_interval"
        />
        <x-select
            class="mb-1"
            label="Ausführungszeitpunkt"
            :options="['Ausführungstag', 'Anfang des Monats', 'Ende des Monats']"
            wire:model="itmSubscriptionForm.execution_time"
        />
        <div class="flex flex-row justify-between mt-4">
            <x-radio id="is_periodic" label="Leistungsdatum" wire:model="itmSubscriptionForm.is_periodic" value="false" />
            <x-radio id="is_periodic" label="Leistungszeitraum" wire:model="itmSubscriptionForm.is_periodic" value="true" />
        </div>
        <div class="flex flex-row justify-between mt-4">
            <x-toggle id="is_active" wire:model="itmSubscriptionForm.is_active" label="Aktiv" name="toggle" />
            <x-toggle id="is_backdated" wire:model="itmSubscriptionForm.is_backdated" label="Rückwirkend" name="toggle" />
        </div>
        <x-button positive full class="mt-4" wire:click="save()">{{ __('Save') }}</x-button>
    </x-card>
</div>
