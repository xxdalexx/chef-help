<div class="form-floating {{ $columnClass }}" wire:key="select-ingredient">

    <select {{ $livewireAttributes }} class="form-select" id="ingredient">
        @foreach($ingredients as $ingredient)
            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
        @endforeach
    </select>

    <label for="ingredient" class="ms-2">Ingredient</label>

</div>
