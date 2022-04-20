<div class="form-floating {{ $columnClass }}" wire:key="select-category">

    <select {{ $livewireAttributes }} class="form-select" id="category">
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label for="category" class="ms-2">Menu Category</label>

</div>
