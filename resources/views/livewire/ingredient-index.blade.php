<?php
/** @var \App\Models\Ingredient $ingredient */
/** @var \Illuminate\Database\Eloquent\Collection $ingredients */
?>

<div>
    <div class="display-1 text-center mb-5">Ingredients</div>

    <div class="row">
        <div class="col-md-4 offset-md-8 col-sm-12 float-end">
            <input wire:model.debounce="searchString" class="form-control" type="search" placeholder="Search" aria-label="Search">
        </div>
    </div>

    <div class="table-responsive">


        <table class="table">
            <thead>
            <tr>
                <th scope="col" style="width: 33%;">Name</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ingredients as $ingredient)
                <tr>
                    <td>
                        <a href="">{{ $ingredient->name }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-row-reverse">
        <div class="float-end">
            {{ $ingredients->links() }}
        </div>
    </div>

    <hr>
</div>
