<div>
    <div class="display-1 text-center mb-5">Recipes</div>

    <div class="row">
        <div class="col-md-4 offset-md-8 col-sm-12 float-end">
            <input wire:model.debounce="search" class="form-control" type="search" placeholder="Search" aria-label="Search">
        </div>
    </div>

    <div class="table-responsive">


    <table class="table">
        <thead>
            <tr>
                <th scope="col" style="width: 33%;">Name</th>
                <th scope="col" class="text-center" style="width: 33%;">Menu Price</th>
                <th scope="col" class="text-end" style="width: 33%;">Current Cost %</th>
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
    </div>

    <div class="d-flex flex-row-reverse">
        <div class="float-end">
            {{ $recipes->links() }}
        </div>
    </div>

    <hr>

    <div class="row mb-5">
    @if($showCreateForm)
        <div class="display-5 text-center mb-5 mt-5">New Recipe</div>

        <div class="row">

            <div class="form-floating col-sm-7 mb-3">
                <input wire:model="recipeNameInput"
                       type="text"
                       class="form-control @error('recipeNameInput') is-invalid @enderror"
                       placeholder="">
                <label class="ms-1">Recipe Name</label>

                @error('recipeNameInput')
                <div class="invalid-feedback">
                    Please provide a valid name.<br>
                    Required
                </div>
                @enderror
            </div>

            <div class="form-floating col-sm">
                <input wire:model="menuPriceInput"
                       type="text"
                       class="form-control @error('menuPriceInput') is-invalid @enderror"
                       placeholder="Menu Price">
                <label class="ms-1">Menu Price</label>

                @error('menuPriceInput')
                <div class="invalid-feedback">
                    Please provide a valid price.<br>
                    Required, must be a number.
                </div>
                @enderror
            </div>

            <div class="form-floating col-sm">
                <input wire:model="portionsInput"
                       type="text"
                       class="form-control @error('portionsInput') is-invalid @enderror"
                       placeholder="Portions">
                <label class="ms-1">Portions Yielded</label>

                @error('portionsInput')
                <div class="invalid-feedback">
                    Please provide a valid portion amount.<br>
                    Required, must be a number.
                </div>
                @enderror
            </div>

        </div>

        <div class="d-grid gap-2 mt-2 row">
            <button wire:click="createRecipe" class="btn btn-success" type="button">
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

</div>
