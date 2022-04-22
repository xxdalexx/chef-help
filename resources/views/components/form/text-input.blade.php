<div class="{{ $columnClass }}">
    <div class="form-floating mb-3" wire:key="{{ $name }}">
        <input wire:model="{{ $name }}"
               type="text"
               class="form-control @error($name) is-invalid @enderror"
               placeholder="">

        <label>
            {{ $labelName }}
        </label>

        @error($name)
        <div class="invalid-feedback">
            Please provide a valid {{ $labelName }}<br>
        </div>
        @enderror
    </div>
</div>
