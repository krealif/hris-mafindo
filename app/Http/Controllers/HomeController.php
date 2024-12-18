<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public static string $HOME = '/';

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        return view('hris.home');
    }
}
