<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectImage;
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
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg',
                'location' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'status' => 'required|boolean',
                'description' => 'nullable|string',
                'date' => 'nullable|date',
            ]);

            $project = Project::create([
                'title' => $request->title,
                'location' => $request->location,
                'category_id' => $request->category_id,
                'status' => (bool)$request->status,
                'description' => $request->description,
                'date' => $request->date,
            ]);

            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('storage/projects/cover/' . $filename);

                // Carregar a imagem sem alterar suas dimensões
                $img = Image::make($file->getRealPath());

                // Salvar a imagem com compressão para reduzir o tamanho do arquivo
                $img->save($path, 75); // Ajuste a qualidade conforme necessário, valores menores reduzem mais o tamanho

                $imagePath = 'projects/cover/' . $filename;
                Cover::create([
                    'path' => '/storage/' . $imagePath,
                    'file_name' => $file->getClientOriginalName(),
                    'project_id' => $project->id,
                ]);
            }

            return redirect()->route('admin.projetos.index')->with('success', 'Projeto criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validação. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar o projeto: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $project = Project::findOrFail($id);
            $categories = Category::all();
            return view('admin-projects.project-edit', compact('project', 'categories'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Projeto não encontrado.');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Ocorreu um erro ao tentar editar o projeto: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg',
                'location' => 'nullable|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'status' => 'nullable|boolean',
                'description' => 'nullable|string',
                'date' => 'nullable|date',
            ]);

            $project = Project::findOrFail($id);

            $project->update(array_filter([
                'title' => $request->title,
                'location' => $request->location,
                'category_id' => $request->category_id,
                'status' => (bool)$request->status,
                'description' => $request->description,
                'date' => $request->date,
            ]));

            if ($request->hasFile('cover')) {
                if ($project->cover) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->url));
                    $project->cover->delete();
                }

                $file = $request->file('cover');
                $imagePath = $file->store('projects/cover', 'public');
                Cover::create([
                    'path' => '/storage/' . $imagePath,
                    'file_name' => $file->getClientOriginalName(),
                    'project_id' => $project->id,
                ]);
            }

            return redirect()->route('admin.projetos.edit', $project->id)->with('success', 'Projeto atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validação. Por favor, verifique os dados inseridos.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Projeto não encontrado.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o projeto: ' . $e->getMessage());
        }
    }



    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            if ($project->cover) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->url));
                $project->cover->delete();
            }

            $project->delete();

            return redirect()->route('admin.projetos.index')->with('success', 'Projeto excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Erro ao excluir o projeto: ' . $e->getMessage());
        }
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

    public function addImage(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg',
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $imagePath = $file->store('projects/images', 'public');

                    ProjectImage::create([
                        'path' => '/storage/' . $imagePath,
                        'file_name' => $file->getClientOriginalName(),
                        'project_id' => $project->id,
                    ]);
                }
            }

            return redirect()->route('admin.projetos.edit', $project->id)->with('success', 'Imagens adicionadas com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validação. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao adicionar as imagens: ' . $e->getMessage());
        }
    }

    public function deleteImage($projectId, $imageId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $image = ProjectImage::findOrFail($imageId);

            Storage::disk('public')->delete(str_replace('/storage/', '', $image->path));
            $image->delete();

            return redirect()->route('admin.projetos.edit', $project->id)->with('success', 'Imagem excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.edit', $project->id)->with('error', 'Ocorreu um erro ao excluir a imagem: ' . $e->getMessage());
        }
    }
}
