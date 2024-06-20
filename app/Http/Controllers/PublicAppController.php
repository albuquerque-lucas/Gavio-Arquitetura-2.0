<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicAppController extends Controller
{
    public function renderHomePage()
    {
        $title = 'Gávio Arquitetura e Interiores | Home';
        return view('public.home-page', compact('title'));
    }
}
