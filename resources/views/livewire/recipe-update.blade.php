<x-modal title="Editing"
    action-button-text="Update"
    id="updateRecipeModal"
    size="medium"
    wire:click="update"
>
    <form wire:submit.prevent="update">
        <x-input-groups.recipe />
    </form>
</x-modal>
