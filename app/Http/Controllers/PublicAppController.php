<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class PublicAppController extends Controller
{
    public function renderHomePage()
    {
        $title = 'Gávio Arquitetura e Interiores | Home';
        $projects = Project::where('status', true)->get();

        return view('public.home-page', compact('title', 'projects'));
    }
}
