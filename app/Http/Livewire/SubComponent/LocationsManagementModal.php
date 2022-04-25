<?php

namespace App\Http\Livewire\SubComponent;

use App\Http\Livewire\LivewireBaseComponent;
use App\Http\Livewire\Plugins\Refreshable;
use App\Models\Location;

class LocationsManagementModal extends LivewireBaseComponent
{
    use Refreshable;

    public string $nameInput = '';

    protected array $rules = [
        'nameInput' => 'required'
    ];

    public function getLocations(): \Illuminate\Database\Eloquent\Collection
    {
        return Location::all();
    }

    public function deleteLocation(Location $location)
    {
        $location->delete();
        $this->alertWithToast('Location Deleted.');
        $this->emitUp('refresh');
    }

    public function createLocation()
    {
        $this->validate();

        Location::create([
            'name' => $this->nameInput,
        ]);

        $this->alertWithToast('Location Created.');
        $this->nameInput = '';
        $this->emitUp('refresh');
    }

    public function render()
    {
        return view('livewire.sub-component.locations-management-modal');
    }
}
