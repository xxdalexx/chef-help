<div class="form-floating {{ $columnClass }}" wire:key="select-location">

    <select {{ $attributes->wire('model') }} class="form-select" id="location">
        @foreach($locations as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>

    <label for="location" class="ms-2">Location</label>

</div>
