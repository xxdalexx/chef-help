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

    <hr>

    @if($showCreateForm)
        <div class="display-5 text-center m-5">New Recipe</div>

        <div class="row g-3">
            <div class="form-floating col-sm-7 mb-3">
                <input type="text" class="form-control" placeholder="">
                <label>Recipe Name</label>
            </div>
            <div class="form-floating col-sm">
                <input type="text" class="form-control" placeholder="Menu Price">
                <label>Menu Price</label>
            </div>
            <div class="form-floating col-sm">
                <input type="text" class="form-control" placeholder="Portions">
                <label>Portions Yielded</label>
            </div>
        </div>

        <div class="d-grid gap-2 mt-2">
            <button wire:click="$toggle('showCreateForm')" class="btn btn-success" type="button">
                Create New Recipe
            </button>
        </div>
    @else
        <div class="d-grid gap-2">
            <button wire:click="$toggle('showCreateForm')" class="btn btn-primary" type="button">
                Create New Recipe
            </button>
        </div>
    @endif

</div>
