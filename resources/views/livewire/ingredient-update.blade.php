<div>
    <x-modal title="Add Conversion"
             action-button-text="Update"
             id="ingredientUpdateModal"
             size="medium"
             wire:click="update"
    >
        <form wire:submit.prevent="update">
            <x-form.text-input name="nameInput" label-name="Name"/>
            <x-form.text-input name="cleanedYieldInput" label-name="Cleaned Yield %"/>
            <x-form.text-input name="cookedYieldInput" label-name="Cooked Yield %"/>
            <button class="visually-hidden" type="submit"/>
        </form>
    </x-modal>
</div>
