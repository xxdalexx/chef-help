<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CostingGoalDifference extends Component
{
    public string $number;

    public function __construct(mixed $number)
    {
        $this->number = (string) $number;
    }

    public function textClass(): string
    {
        return $this->greaterThanZero() ? 'text-danger' : 'text-success';
    }

    public function sign(): string
    {
        return $this->greaterThanZero() ? '+' : '';
    }

    protected function greaterThanZero(): bool
    {
        return $this->number > 0;
    }

    public function render()
    {
        return view('components.costing-goal-difference');
    }
}
