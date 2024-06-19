<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Cover;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Exception;

class AdminProjectController extends Controller
{
    public function index()
    {
        $projectsResult = Project::orderBy('id', 'desc')->paginate();
        $projectsResultList = $projectsResult->toArray();
        $links = $projectsResultList['links'];
        $projects = $projectsResult->items();
        return view('admin-projects.project-list', compact('projects', 'links'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin-projects.project-create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            // Validação dos dados de entrada
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'location' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'status' => 'required|boolean',
                'description' => 'nullable|string',
                'date' => 'nullable|date',
            ]);

            // Criação do projeto
            $project = Project::create([
                'title' => $request->title,
                'location' => $request->location,
                'category_id' => $request->category_id,
                'status' => (bool)$request->status,  // Conversão explícita para booleano
                'description' => $request->description,
                'date' => $request->date,
            ]);

            // Verificação e armazenamento da imagem de capa, se presente
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $imagePath = $file->store('projects/cover', 'public');
                Cover::create([
                    'path' => '/storage/' . $imagePath,
                    'file_name' => $file->getClientOriginalName(),
                    'project_id' => $project->id,
                ]);
            }

            // Redirecionamento com mensagem de sucesso
            return redirect()->route('admin.projetos.index')->with('success', 'Projeto criado com sucesso!');
        } catch (ValidationException $e) {
            // Captura erros de validação (422 Unprocessable Entity)
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validação. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            // Captura todos os outros tipos de erro
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar o projeto: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $categories = Category::all();
        return view('admin-projects.project-edit', compact('project', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'location' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|string|in:Ativo,Inativo',
        ]);

        $project = Project::findOrFail($id);

        $project->update(array_filter($request->only(['title', 'location', 'category_id', 'status'])));

        if ($request->hasFile('cover')) {
            if ($project->cover) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->url));
                $project->cover->delete();
            }

            $cover = new Cover();
            $imagePath = $request->file('cover')->store('projects/covers', 'public');
            $cover->url = '/storage/' . $imagePath;
            $cover->project_id = $project->id;
            $cover->save();
        }

        return redirect()->route('admin.projetos.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->cover) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->url));
            $project->cover->delete();
        }

        $project->delete();

        return redirect()->route('admin.projetos.index')->with('success', 'Projeto excluído com sucesso!');
    }

    public function toggleCarousel($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->status = !$project->status;
            $project->save();

            return redirect()->route('admin.projetos.index')->with('success', 'Status do projeto atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Erro ao atualizar o status do projeto: ' . $e->getMessage());
        }
    }
}
