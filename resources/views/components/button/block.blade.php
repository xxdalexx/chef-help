<div {{ $divAttributes }} >
    <button {{ $buttonAttributes }} class="btn btn-{{ $styleType }}" type="button" wire:loading.attr="disabled">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="{{ $livewireMethod }}"></span>
        {{ $text }}
    </button>
</div>
