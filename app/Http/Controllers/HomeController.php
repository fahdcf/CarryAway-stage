<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil de CarryWay.
     */
    public function index(): View
    {
        return view('homepage-standalone');
    }
}
