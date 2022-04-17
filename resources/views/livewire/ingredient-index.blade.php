<?php
/** @var \App\Models\Ingredient $ingredient */
/** @var \Illuminate\Database\Eloquent\Collection $ingredients */
?>

<div>
    <div class="display-1 text-center mb-5">Ingredients</div>

    <x-livewire.search-box />

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
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

    Create Stuff
</div>
