<?php
/** @var \App\Models\MenuCategory $category */
?>

<div>
    <div class="display-1 text-center mb-5">Recipe Menu Categories</div>

    <div class="row">
        <div class="col-md-6 overflow-visible">
            <x-table>
                <x-slot:heading>
                    <th scope="col" style="width: 33%;" />
                    <th scope="col" class="text-center" style="width: 33%;">Costing Goal</th>
                    <th scope="col" class="text-end" style="width: 33%;">Recipes</th>
                    <th scope="col" class="text-end" />
                </x-slot:heading>

                @foreach($menuCategories as $category)
                    <tr>
                        <td>
                            {{ $category->name }}
                        </td>
                        <td class="text-center">
                            {{ $category->getCostingGoalAsString() }}%
                        </td>
                        <td class="text-end">
                            {{ $category->recipes_count }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                    <li>
                                        <a wire:click="loadMenuCategoryToEdit({{ $category->id }})" class="dropdown-item" href="#">Edit</a>
                                    </li>
                                    <li>
                                        <a wire:click="deleteMenuCategory({{ $category->id }})" class="dropdown-item" href="#">Delete</a>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <a class="dropdown-item" href="#">Show Recipes</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </div>
        <div class="col-md-6">
            <x-card :title="$this->cardTitle">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.text-input name="nameInput" label-name="Name" cols="12"/>
                    </div>

                    <div class="col-md-6">
                        <x-form.text-input name="costingGoalInput" label-name="Costing Percentage Goal" cols="12"/>
                    </div>
                </div>
                <x-button.block wire:click="process" text="Save" />
            </x-card>
        </div>
    </div>

</div>
