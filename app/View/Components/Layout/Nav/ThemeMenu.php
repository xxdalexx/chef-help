<?php

namespace App\View\Components\Layout\Nav;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ThemeMenu extends Component
{
    public Collection $themes;

    public array $current;

    protected Collection $availableThemes;

    public function __construct()
    {
        $this->availableThemes = collect(config('app.themes'));

        $this->makeThemesCollection();

        $this->current = $this->themes->pull(
            $this->getValidatedThemeString()
        );
    }

    protected function makeThemesCollection(): void
    {
        $toPush = [];
        foreach ($this->availableThemes as $theme) {
            $toPush[$theme] =
                [
                    'title' => ucwords($theme),
                    'route' => route('change-theme', $theme)
                ];
        }
        $this->themes = collect($toPush);
    }

    protected function getValidatedThemeString(): string
    {
        $fromSession = session('theme', $this->availableThemes->first());

        if ($this->availableThemes->contains($fromSession)) {
            return $fromSession;
        }

        return $this->availableThemes->first();
    }

    public function render()
    {
        return view('components.layout.nav.theme-menu');
    }
}
