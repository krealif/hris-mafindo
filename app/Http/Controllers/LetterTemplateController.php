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
        return view('hris.surat.user.list-template', [
            'templates' => LetterTemplate::all('name', 'view'),
        ]);
    }
}
