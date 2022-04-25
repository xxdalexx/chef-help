<div class="form-floating {{ $columnClass }}" wire:key="select-category{{ $num ?? 1 }}">

    <select {{ $livewireAttributes }} class="form-select form-select-sm" id="category" wire:key="category-select{{ $num ?? '1' }}">
        <option value="">All</option>
        @foreach($categories as $category)
            <option>{{ $category->name }}</option>
        @endforeach
    </select>

    <label for="category">Menu Category</label>

</div>
