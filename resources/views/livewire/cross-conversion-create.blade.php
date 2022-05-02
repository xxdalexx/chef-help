<div>
    <x-modal title="Add Conversion"
             action-button-text="Create"
             id="crossConversionCreateModal"
             size="medium"
             wire:click="create"
    >
        <form wire:submit.prevent="create">
            <x-form.text-input name="quantityOneInput" label-name="Quantity"/>
            <x-form.select-units wire:model="unitOneInput"/>
            <x-form.text-input name="quantityTwoInput" label-name="Quantity"/>
            <x-form.select-units wire:model="unitTwoInput"/>
            <button type="submit" class="visually-hidden"/>
        </form>
    </x-modal>
</div>

