<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    public function __invoke(string $theme): RedirectResponse
    {
        Session::put('theme', $theme);

        return redirect()->back();
    }
}
