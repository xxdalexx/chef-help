<div class="form-floating {{ $columnClass }} mb-3" wire:key="select-units">

    <select {{ $livewireAttributes }} class="form-select" id="ingredient">
        @foreach($units as $unit)
            <option value="{{ $unit->value }}">{{ $unit->value }}</option>
        @endforeach
    </select>

    <label for="ingredient" class="ms-2">Unit</label>

</div>
