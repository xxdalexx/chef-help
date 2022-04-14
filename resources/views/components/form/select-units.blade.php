<div class="form-floating {{ $columnClass }}" wire:key="select-units">

    <select {{ $livewireAttributes }} class="form-select" id="ingredient">
        @foreach($units as $unit)
            <option value="{{ $unit->value }}">{{ $unit->value }}</option>
        @endforeach
    </select>

    <label for="ingredient" class="ms-2">Unit</label>

</div>
