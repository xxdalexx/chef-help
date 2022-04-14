<?php

namespace App\Http\Livewire\Plugins;

trait Refreshable
{
    public function initializeRefreshable()
    {
        $string = 'refresh' . class_basename($this);
        $this->listeners[$string] = '$refresh';
    }
}
