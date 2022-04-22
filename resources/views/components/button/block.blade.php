<div {{ $divAttributes }} >
    <button {{ $buttonAttributes }}
            class="btn btn-{{ $styleType }}"
            type="button"
            @if($showSpinner) wire:loading.attr="disabled" @endif>

        @if($showSpinner)
        <span class="spinner-border spinner-border-sm"
              role="status"
              aria-hidden="true"
              wire:loading
              wire:target="{{ $livewireMethod }}">
        </span>
        @endif

        {{ $text }}

    </button>
</div>
