<?php
/** @var \App\Models\Ingredient $ingredient */
/** @var \Illuminate\Database\Eloquent\Collection $ingredients */
?>

<div>
    <div class="display-1 text-center mb-5">Ingredients</div>

    <x-livewire.search-box />

    <x-table>
        <x-slot:heading>
            <th scope="col">Name</th>
        </x-slot:heading>

        @foreach($ingredients as $ingredient)
            <tr>
                <td>
                    <a href="">{{ $ingredient->name }}</a>
                </td>
            </tr>
        @endforeach
    </x-table>

    <x-pagination-links :paginated="$ingredients"/>

    <hr>

    Create Stuff
</div>
