<div class="d-grid gap-2" >
    <button class="btn btn-success" type="submit" wire:target="{{ $targets }}" wire:loading.attr="disabled">

        <span class="spinner-border spinner-border-sm"
              role="status"
              aria-hidden="true"
              wire:loading
              wire:target="{{ $targets }}">
        </span>
        {{ $text }}
    </button>
</div>
