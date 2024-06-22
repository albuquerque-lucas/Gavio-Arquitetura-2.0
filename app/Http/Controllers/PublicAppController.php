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

    public function renderProjectsPage($categoryId = 1)
    {
        $title = 'Projetos | Gávio Arquitetura e Interiores';
        $projects = Project::where('category_id', $categoryId)->orderBy('id', 'desc')->paginate();
        $projectsList = $projects->toArray();
        $links = $projectsList['links'];
        $categories = Category::all();

        return view('public.projects-page', compact('title', 'projects', 'categories', 'categoryId', 'links'));
    }

    public function showProject($id)
    {
        $project = Project::findOrFail($id);
        $title = $project->title;
        return view('public.project-show', compact('project', 'title'));
    }
}
