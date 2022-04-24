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
                                    @if($menuCategories->count() > 1)
                                    <li>
                                        <a wire:click="deleteMenuCategory({{ $category->id }})" class="dropdown-item" href="#">Delete</a>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('recipe.index', ['menuCategory' => $category->name]) }}">Show Recipes</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </div>
        <div class="col-md-6">
            <x-card :title="$this->cardTitle">
                <form wire:submit.prevent="process">
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.text-input name="nameInput" label-name="Name" cols="12"/>
                        </div>

                        <div class="col-md-6">
                            <x-form.text-input name="costingGoalInput" label-name="Costing Percentage Goal" cols="12"/>
                        </div>
                    </div>
                    <x-ls.submit-button targets="process" />
                </form>
            </x-card>
        </div>
    </div>

    {{-- Modal --}}

    <x-modal wire:click="moveAllToCategory" title="Move Recipes to Category" id="moveModal" action-button-text="Move">
        All recipes must be moved to another category before deleting.
        <hr>

        <div class="form-floating" wire:key="select-category">

            <select wire:model="categoryIdToMoveTo" class="form-select" id="category">
                @foreach($this->categoriesForMove as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <label for="category">Menu Category</label>

        </div>
    </x-modal>

</div>
