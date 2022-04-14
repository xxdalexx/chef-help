<?php

namespace App\View\Components\Form;

use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class SelectUnits extends Component
{
    public string $columnClass;

    public function __construct(?int $cols = null)
    {
        $this->columnClass = $cols
            ? "col-md-$cols"
            : "col";
    }

    public function units(): Collection
    {
        return collect(UsWeight::cases())
            ->merge(UsVolume::cases())
            ->merge(MetricWeight::cases())
            ->merge(MetricVolume::cases());
    }

    public function livewireAttributes(): ComponentAttributeBag
    {
        return $this->attributes->thatStartWith('wire');
    }

    public function render()
    {
        return view('components.form.select-units');
    }
}
