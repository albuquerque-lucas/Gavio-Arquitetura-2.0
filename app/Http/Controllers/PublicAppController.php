<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class PublicAppController extends Controller
{
    public function renderHomePage()
    {
        $title = 'Home | Gávio Arquitetura e Interiores';
        $projects = Project::where('status', true)->get();

        return view('public.home-page', compact('title', 'projects'));
    }

    public function renderAboutUsPage()
    {
        $title = 'Quem somos | Gávio Arquitetura e Interiores';
        $profiles = User::where('ownership', true)->get();

        return view('public.institutional-page', compact('title', 'profiles'));
    }
}
