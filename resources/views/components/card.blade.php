<div class="card {{ $cardClass ?? '' }}">
    <h5 class="card-header {{ $headerClass ?? '' }} d-flex justify-content-between">
        {{ $title }}
        @if($attributes->has('edit-click'))
            <i wire:click="{{ $attributes->get('edit-click') }}" class="bi bi-pencil-square cursor-pointer"></i>
        @endif
        @if($attributes->has('close-click'))
            <i wire:click="{{ $attributes->get('close-click') }}" class="bi bi-x-lg cursor-pointer"></i>
        @endif
        @if($attributes->has('add-click'))
            <i wire:click="{{ $attributes->get('add-click') }}" class="bi bi-plus-lg cursor-pointer"></i>
        @endif

    </h5>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
