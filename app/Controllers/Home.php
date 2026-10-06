<?php

namespace App\Controllers;

use App\Libraries\ActivityCatalog;

/**
 * GET / — the activity picker: choose TFA 2, TFA 3 or TFA 4.
 */
class Home extends BaseController
{
    public function index(): string
    {
        return view('home', [
            'title'      => 'Activities',
            'activities' => ActivityCatalog::all(),
            'isLoggedIn' => session()->get('isLoggedIn') === true,
        ]);
    }
}
