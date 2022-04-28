<x-modal title="Create New Recipe"
    action-button-text="Create"
    id="createRecipeModal"
    size="medium"
    wire:click="create"
>
    <form wire:submit.prevent="create">
        <x-input-groups.recipe />
    </form>
</x-modal>
