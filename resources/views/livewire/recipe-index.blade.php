<div>
    <div class="display-1 text-center mb-5">Recipes</div>

    <div class="row">
        <div class="col-md-4 offset-md-8 col-sm-12 float-end">
            <input wire:model.debounce="search" class="form-control" type="search" placeholder="Search" aria-label="Search">
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col" class="text-center">Menu Price</th>
                <th scope="col" class="text-end">Current Cost %</th>
            </tr>
        </thead>
        <tbody>
            <?php
                /** @var \App\Models\Recipe $recipe */
            ?>
            @foreach($recipes as $recipe)
                <tr>
                    <td>
                        <a href="{{ $recipe->showLink() }}">{{ $recipe->name }}</a>
                    </td>
                    <td class="text-center">
                        {{ $recipe->getPriceAsString() }}
                    </td>
                    <td class="text-end">
                        {{ $recipe->getPortionCostPercentageAsString() }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="float-end">
        {{ $recipes->links() }}
    </div>
</div>
