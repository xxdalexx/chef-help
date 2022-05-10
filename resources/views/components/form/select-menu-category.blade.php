<div class="form-floating {{ $columnClass }} mb-3" wire:key="select-category">

    <select {{ $livewireAttributes }} class="form-select" id="category">
        <option value="">None</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label for="category">Menu Category</label>

</div>
