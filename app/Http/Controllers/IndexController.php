<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class IndexController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Index/Index',
            [
                'message' => 'This is from Laravel component!'
            ]
        );
    }

    public function show()
    {
        return inertia('Index/Show');
    }
}
