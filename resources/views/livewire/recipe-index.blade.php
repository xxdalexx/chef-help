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

            <x-form.text-input name="recipeNameInput" label-name="Recipe Name" cols="7" />
            <x-form.text-input name="menuPriceInput" label-name="Menu Price" />
            <x-form.text-input name="portionsInput" label-name="Portions" />

        </div>

        <x-button.block
            wire:click="createRecipe"
            class="row mt-2"
            style-type="success"
            text="Create New Recipe"/>
    @else
        <x-button.block wire:click="$toggle('showCreateForm')" text="Create New Recipe" />
    @endif
    </div>

</div>
