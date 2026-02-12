<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreFormRequest;
use App\Http\Requests\ProjectUpdateFormRequest;
use App\Models\Category;
use App\Models\Cover;
use App\Models\Project;
use App\Models\ProjectImage;
use App\ProcessesImages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        $hasPages = $projects->hasPages();

        return view('admin-projects.project-list', compact('projects', 'categories', 'hasPages'));
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
                'status' => (bool) $validated['status'],
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

    public function edit(Project $projeto)
    {
        try {
            $project = $projeto;
            $categories = Category::all();
            return view('admin-projects.project-edit', compact('project', 'categories'));
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Ocorreu um erro ao tentar editar o projeto: ' . $e->getMessage());
        }
    }

    public function update(ProjectUpdateFormRequest $request, Project $projeto)
    {
        try {
            $validated = $request->validated();
            $project = $projeto;

            $project->update([
                'title' => $validated['title'],
                'location' => $validated['location'],
                'area' => $validated['area'],
                'category_id' => $validated['category_id'],
                'status' => isset($validated['status']) ? (bool) $validated['status'] : $project->status,
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

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Projeto atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validacao. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o projeto: ' . $e->getMessage());
        }
    }

    public function addImage(Request $request, Project $project)
    {
        try {
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

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Imagens adicionadas com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validacao. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao adicionar as imagens: ' . $e->getMessage());
        }
    }

    public function destroy(Project $projeto)
    {
        try {
            $project = $projeto;

            if ($project->cover) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $project->cover->path));
                $project->cover->delete();
            }

            foreach ($project->images as $image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $image->path));
                $image->delete();
            }

            $project->delete();

            return redirect()->route('admin.projetos.index')->with('success', 'Projeto excluido com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Erro ao excluir o projeto: ' . $e->getMessage());
        }
    }

    public function toggleCarousel(Project $project)
    {
        try {
            $project->status = !$project->status;
            $project->save();

            return redirect()->route('admin.projetos.index')->with('success', 'Status do projeto atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.index')->with('error', 'Erro ao atualizar o status do projeto: ' . $e->getMessage());
        }
    }

    public function deleteImage(Project $project, $imageId)
    {
        try {
            $image = ProjectImage::findOrFail($imageId);
            Storage::disk('public')->delete(str_replace('/storage/', '', $image->path));
            $image->delete();

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Imagem excluida com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.edit', $project)->with('error', 'Ocorreu um erro ao excluir a imagem: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $uuids = json_decode($request->input('selected_projects', '[]'));

        if (empty($uuids)) {
            return redirect()->route('admin.projetos.index')->with('error', 'Nenhum projeto foi selecionado para exclusao.');
        }

        \DB::transaction(function () use ($uuids) {
            Project::whereIn('uuid', $uuids)->each(function (Project $project) {
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

        return redirect()->route('admin.projetos.index')->with('success', 'Projetos selecionados foram excluidos com sucesso!');
    }

    public function updateOrder(Request $request, Project $project)
    {
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

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'ordered_projects' => 'required|array|min:1',
            'ordered_projects.*' => 'required|uuid',
        ]);

        $orderedUuids = $validated['ordered_projects'];
        $projects = Project::whereIn('uuid', $orderedUuids)->get()->keyBy('uuid');

        if ($projects->count() !== count($orderedUuids)) {
            return response()->json([
                'message' => 'Falha ao ordenar projetos: lista invalida.',
            ], 422);
        }

        $baseOrder = (int) $projects->min('order');

        \DB::transaction(function () use ($orderedUuids, $projects, $baseOrder) {
            foreach ($orderedUuids as $index => $uuid) {
                $project = $projects->get($uuid);
                $project->update(['order' => $baseOrder + $index]);
            }
        });

        return response()->json([
            'message' => 'Ordem dos projetos atualizada com sucesso!',
        ]);
    }

    public function bulkDeleteImages(Request $request, Project $project)
    {
        try {
            $imageIds = $request->input('selected_images', []);

            if (empty($imageIds)) {
                return redirect()->route('admin.projetos.edit', $project)->with('error', 'Nenhuma imagem foi selecionada para exclusao.');
            }

            \DB::transaction(function () use ($imageIds) {
                foreach ($imageIds as $imageId) {
                    $image = ProjectImage::findOrFail($imageId);
                    Storage::disk('public')->delete(str_replace('/storage/', '', $image->path));
                    $image->delete();
                }
            });

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Imagens selecionadas foram excluidas com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.projetos.edit', $project)->with('error', 'Ocorreu um erro ao excluir as imagens: ' . $e->getMessage());
        }
    }
}
