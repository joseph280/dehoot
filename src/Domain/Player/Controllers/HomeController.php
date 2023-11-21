<?php

namespace Domain\Player\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home');
    }
}
