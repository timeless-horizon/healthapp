<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;

class FaqController extends Controller
{
    public function faq()
    {
        return inertia::render('Guest/Faq');
    }
}
