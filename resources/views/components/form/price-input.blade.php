<div class="{{ $columnClass }}">
    <div class="input-group mb-3" wire:key="{{ $name }}">
        <span class="input-group-text">$</span>
        <div class="form-floating flex-grow-1">
            <input wire:model="{{ $name }}"
                   type="text"
                   class="form-control @error($name) is-invalid @enderror"
                   placeholder="">

            <label>
                {{ $labelName }}
            </label>
        </div>

        @error($name)
        <div class="invalid-feedback d-block">
            Please provide a valid {{ $labelName }}<br>
        </div>
        @enderror

    </div>
</div>
