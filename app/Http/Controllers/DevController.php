<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevController extends Controller
{
    public function __construct()
    {
        if (!app()->environment('local')) {
            abort(404);
        }
    }

    public function index()
    {

    }
}
