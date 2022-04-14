<div class="form-floating {{ $columnClass }} mb-3">
    <input wire:model="{{ $name }}"
           type="text"
           class="form-control @error($name) is-invalid @enderror"
           placeholder="">

    <label class="ms-1">
        {{ $labelName }}
    </label>

    @error($name)
    <div class="invalid-feedback">
        Please provide a valid {{ $labelName }}<br>
    </div>
    @enderror
</div>
