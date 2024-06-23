<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Category;

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

    public function renderProjectsPage($slug = 'residencial')
    {
        $title = 'Projetos | Gávio Arquitetura e Interiores';

        $category = Category::where('slug', $slug)->firstOrFail();

        $projects = Project::where('category_id', $category->id)->orderBy('id', 'desc')->paginate();
        $projectsList = $projects->toArray();
        $links = $projectsList['links'];
        $categories = Category::all();

        return view('public.projects-page', compact('title', 'projects', 'categories', 'category', 'links'));
    }


    public function showProject($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $title = "$project->title | Gávio Arquitetura e Interiores";
        return view('public.project-show', compact('project', 'title'));
    }
    public function renderContactPage()
    {
        $title = 'Contate-nos | Gávio Arquitetura e Interiores';
        return view('public.contact-us', compact('title'));
    }
}
