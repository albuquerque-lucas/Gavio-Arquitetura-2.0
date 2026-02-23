<?php

namespace App\Http\Controllers;

use App\Actions\Projects\AddProjectImagesAction;
use App\Actions\Projects\BulkDeleteProjectImagesAction;
use App\Actions\Projects\BulkDeleteProjectsAction;
use App\Actions\Projects\CreateProjectAction;
use App\Actions\Projects\DeleteProjectAction;
use App\Actions\Projects\DeleteProjectImageAction;
use App\Actions\Projects\ReorderProjectsAction;
use App\Actions\Projects\ToggleProjectCarouselAction;
use App\Actions\Projects\UpdateProjectAction;
use App\Http\Requests\ProjectStoreFormRequest;
use App\Http\Requests\ProjectUpdateFormRequest;
use App\Models\Category;
use App\Models\Project;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class AdminProjectController extends Controller
{
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

        $projects = $projectsQuery->orderBy($sortBy, $order)->paginate();
        $categories = Category::all();
        $hasPages = $projects->hasPages();

        return view('admin-projects.project-list', compact('projects', 'categories', 'hasPages'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin-projects.project-create', compact('categories'));
    }

    public function store(ProjectStoreFormRequest $request, CreateProjectAction $createProject)
    {
        try {
            $createProject($request->validated(), $request->file('cover'));

            return redirect()->route('admin.projetos.index')->with('success', 'Projeto criado com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar o projeto. Tente novamente.');
        }
    }

    public function edit(Project $projeto)
    {
        $project = $projeto;
        $categories = Category::all();

        return view('admin-projects.project-edit', compact('project', 'categories'));
    }

    public function update(ProjectUpdateFormRequest $request, Project $projeto, UpdateProjectAction $updateProject)
    {
        try {
            $project = $projeto;
            $updateProject($project, $request->validated(), $request->file('cover'));

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Projeto atualizado com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o projeto. Tente novamente.');
        }
    }

    public function addImage(Request $request, Project $project, AddProjectImagesAction $addProjectImages)
    {
        try {
            $validated = $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:50000',
            ]);

            $files = $validated['images'] ?? [];
            if (! empty($files)) {
                $addProjectImages($project, $files);
            }

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Imagens adicionadas com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao adicionar as imagens. Tente novamente.');
        }
    }

    public function destroy(Project $projeto, DeleteProjectAction $deleteProject)
    {
        try {
            $project = $projeto;
            $deleteProject($project);

            return redirect()->route('admin.projetos.index')->with('success', 'Projeto excluido com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('admin.projetos.index')->with('error', 'Erro ao excluir o projeto. Tente novamente.');
        }
    }

    public function toggleCarousel(Project $project, ToggleProjectCarouselAction $toggleProjectCarousel)
    {
        try {
            $toggleProjectCarousel($project);

            return redirect()->route('admin.projetos.index')->with('success', 'Status do projeto atualizado com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('admin.projetos.index')->with('error', 'Erro ao atualizar o status do projeto. Tente novamente.');
        }
    }

    public function deleteImage(Project $project, int $imageId, DeleteProjectImageAction $deleteProjectImage)
    {
        try {
            $deleteProjectImage($project, $imageId);

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Imagem excluida com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('admin.projetos.edit', $project)->with('error', 'Ocorreu um erro ao excluir a imagem. Tente novamente.');
        }
    }

    public function bulkDelete(Request $request, BulkDeleteProjectsAction $bulkDeleteProjects)
    {
        $uuids = json_decode((string) $request->input('selected_projects', '[]'), true);
        $uuids = is_array($uuids) ? $uuids : [];

        if (empty($uuids)) {
            return redirect()->route('admin.projetos.index')->with('error', 'Nenhum projeto foi selecionado para exclusao.');
        }

        try {
            $bulkDeleteProjects($uuids);

            return redirect()->route('admin.projetos.index')->with('success', 'Projetos selecionados foram excluidos com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('admin.projetos.index')->with('error', 'Erro ao excluir projetos selecionados. Tente novamente.');
        }
    }

    public function reorder(Request $request, ReorderProjectsAction $reorderProjects): JsonResponse
    {
        $validated = $request->validate([
            'ordered_projects' => 'required|array|min:1',
            'ordered_projects.*' => 'required|uuid',
        ]);

        try {
            $reorderProjects($validated['ordered_projects']);

            return response()->json([
                'message' => 'Ordem dos projetos atualizada com sucesso!',
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Erro ao atualizar ordem dos projetos. Tente novamente.',
            ], 500);
        }
    }

    public function bulkDeleteImages(Request $request, Project $project, BulkDeleteProjectImagesAction $bulkDeleteProjectImages)
    {
        try {
            $imageIds = $request->input('selected_images', []);
            $imageIds = is_array($imageIds) ? $imageIds : [];

            if (empty($imageIds)) {
                return redirect()->route('admin.projetos.edit', $project)->with('error', 'Nenhuma imagem foi selecionada para exclusao.');
            }

            $bulkDeleteProjectImages($project, $imageIds);

            return redirect()->route('admin.projetos.edit', $project)->with('success', 'Imagens selecionadas foram excluidas com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('admin.projetos.edit', $project)->with('error', 'Ocorreu um erro ao excluir as imagens. Tente novamente.');
        }
    }
}
