<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Cover;
use App\Models\Category;
use App\ProcessesImages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Requests\ProjectStoreFormRequest;
use App\Http\Requests\ProjectUpdateFormRequest;

class AdminProjectController extends Controller
{
    use ProcessesImages;

    public function index(Request $request)
    {
        $search = $request->input('search');
        $order = $request->input('order', 'desc');
        $sortBy = $request->input('sort_by', 'id');
        $categoryId = $request->input('category_id');

        $projectsQuery = Project::query();

        if ($search) {
            $projectsQuery->where('title', 'LIKE', "%{$search}%");
        }

        if ($categoryId) {
            $projectsQuery->where('category_id', $categoryId);
        }

        $projectsQuery->orderBy($sortBy, $order);

        $projects = $projectsQuery->paginate();

        $categories = Category::all();

        return view('admin-projects.project-list', compact('projects', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin-projects.project-create', compact('categories'));
    }

    public function store(ProjectStoreFormRequest $request)
    {
        try {
            $validated = $request->validated();

            $maxOrder = Project::where('category_id', $request->category_id)->max('order');

            $project = Project::create([
                'title' => $validated['title'],
                'location' => $validated['location'],
                'area' => $validated['area'],
                'category_id' => $validated['category_id'],
                'status' => (bool)$validated['status'],
                'description' => $validated['description'],
                'year' => $validated['year'],
                'order' => $maxOrder + 1,
            ]);

            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('storage/projects/cover/' . $filename);

                $this->processImage($file, $path);

                $imagePath = 'projects/cover/' . $filename;

                Cover::create([
                    'path' => '/storage/' . $imagePath,
                    'file_name' => $file->getClientOriginalName(),
                    'project_id' => $project->id,
                ]);
            }

            return redirect()->route('admin.projetos.index')->with('success', 'Projeto criado com sucesso!');
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

    public function update(ProjectUpdateFormRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $project = Project::findOrFail($id);

            $project->update([
                'title' => $validated['title'],
                'location' => $validated['location'],
                'area' => $validated['area'],
                'category_id' => $validated['category_id'],
                'status' => isset($validated['status']) ? (bool)$validated['status'] : $project->status,
                'description' => $validated['description'],
                'year' => $validated['year'],
            ]);

            if ($request->hasFile('cover')) {
                if ($project->cover) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->path));
                    $project->cover->delete();
                }

                $file = $request->file('cover');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('storage/projects/cover/' . $filename);

                $this->processImage($file, $path);

                $imagePath = 'projects/cover/' . $filename;

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


    public function addImage(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:50000',
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = public_path('storage/projects/images/' . $filename);

                    $this->processImage($file, $path);

                    $imagePath = 'projects/images/' . $filename;

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

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            if ($project->cover) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->path));
                $project->cover->delete();
            }

            foreach ($project->images as $image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $image->path));
                $image->delete();
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

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('selected_projects', '[]'));

        if (empty($ids)) {
            return redirect()->route('admin.projetos.index')->with('error', 'Nenhum projeto foi selecionado para exclusão.');
        }

        \DB::transaction(function () use ($ids) {
            Project::whereIn('id', $ids)->each(function ($project) {
                if ($project->cover) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->path));
                    $project->cover->delete();
                }

                foreach ($project->images as $image) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $image->path));
                    $image->delete();
                }

                $project->delete();
            });
        });

        return redirect()->route('admin.projetos.index')->with('success', 'Projetos selecionados foram excluídos com sucesso!');
    }

    public function updateOrder(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $newOrder = $request->input('order');

        $otherProject = Project::where('category_id', $project->category_id)
            ->where('order', $newOrder)
            ->first();

        if ($otherProject) {
            $otherProject->update(['order' => $project->order]);
        }

        $project->update(['order' => $newOrder]);

        return redirect()->route('admin.projetos.index')->with('success', 'Ordem do projeto atualizada com sucesso!');
    }

    public function bulkDeleteImages()
    {

    }

}
