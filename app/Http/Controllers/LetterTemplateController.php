<?php

namespace App\Http\Controllers;

use App\Models\LetterTemplate;
use Illuminate\View\View;

class LetterTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(): View
    {
        return view('hris.letter.list-template', [
            'letters' => LetterTemplate::all('name', 'view'),
        ]);
    }
}
