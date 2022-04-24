<div class="form-floating {{ $columnClass }}" wire:key="select-category">

    <select {{ $livewireAttributes }} class="form-select form-select-sm" id="category">
        <option value="">All</option>
        @foreach($categories as $category)
            <option>{{ $category->name }}</option>
        @endforeach
    </select>

    <label for="category">Menu Category</label>

</div>
