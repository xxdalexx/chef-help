<table class="table">
    <thead>
    <tr>
        <th scope="col" style="width: 33%;"></th>
        <th scope="col" class="text-center" style="width: 33%;">Amount</th>
        <th scope="col" class="text-end" style="width: 33%;">Cost</th>
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
            <td class="text-end">
                {{ $item->cost }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<x-button.block wire:click="$set('editArea', 'ingredient')" style-type="success" text="Add Ingredient" />
