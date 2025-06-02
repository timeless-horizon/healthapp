<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;

class PrivacyPolicyController extends Controller
{
    public function privacypolicy()
    {
        return inertia::render('Guest/PrivacyPolicy');
    }
}
