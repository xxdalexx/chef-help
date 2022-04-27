<?php
/**
 * @var \App\Models\Location   $location
 */
?>
<div>
    <x-modal title="Locations" id="locationsModal" size="sm" noActionButton>
        <form wire:submit.prevent="createLocation">
            <x-form.text-input name="nameInput" label-name="Create New Location" />
        </form>

        @forelse($this->getLocations() as $location)
            <li class="list-group-item d-flex justify-content-between">
                {{ $location->name }}
                <span>
                    <i wire:click="deleteLocation({{ $location->id }})" class="bi bi-trash text-danger cursor-pointer"></i>
                </span>
            </li>
        @empty
            <li class="list-group-item">No Locations Set</li>
        @endforelse
    </x-modal>
</div>
