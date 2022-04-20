<?php
/** @var \App\Models\Recipe $recipe */
?>
<table class="table">
    <thead>
    <tr>
        <th scope="col" style="width: 65%;"></th>
        <th scope="col" class="text-center" style="width: 15%;">Amount</th>
        <th scope="col" class="text-center" style="width: 10%;">Cost</th>
        <th scope="col" style="width: 10%;"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($recipe->items as $item)
        <tr>
            <td>
                {{ $item->ingredientName }}
            </td>
            <td class="text-center">
                {{ $item->measurement }}
            </td>
            <td class="text-center">
                @if(!$item->canCalculateCost())
                    <span class="text-danger">{{ $item->cost }}</span>
                    <x-icon.warning :tooltip="$item->canNotCalculateCostReason()" />
                @else
                    {{ $item->cost }}
                @endif
            </td>
            <td class="text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                        <li>
                            <a wire:click.prevent="showEditItem({{ $item->id }})" class="dropdown-item" href="#">Edit</a>
                        </li>
                        <li>
                            <a wire:click.prevent="removeRecipeItem({{ $item->id }})" class="dropdown-item" href="#">Remove</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ $item->ingredient->showLink() }}">Ingredient Page</a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<x-button.block wire:click="showAddIngredient" style-type="success" text="Add Ingredient" />
