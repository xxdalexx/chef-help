<div class="modal" tabindex="-1" id="{{ $id }}" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered {{ $sizeClass }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $closeButtonText }}</button>
                @if(! $attributes->has('noActionButton'))
                    <button {{ $attributes->wire('click') }}
                            type="button"
                            class="btn btn-primary"
                            @if(! $errors->any())
                                data-bs-dismiss="modal"
                            @endif
                    >
                        {{ $actionButtonText }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
