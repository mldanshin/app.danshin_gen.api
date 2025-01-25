<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

final class IndexController extends Controller
{
    public function __invoke(): View
    {
        return view('index', ['routes' => Route::getRoutes()->getRoutes()]);
    }
}
