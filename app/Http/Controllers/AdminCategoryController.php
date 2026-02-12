<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate();
        return view('admin-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin-categories.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            Category::create($validated);

            return redirect()->route('admin.categories.index')->with('success', 'Categoria criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validacao. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar a categoria: ' . $e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        try {
            return view('admin-categories.edit', compact('category'));
        } catch (Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Categoria nao encontrada.');
        }
    }

    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category->update($validated);

            return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validacao. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar a categoria: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()->route('admin.categories.index')->with('success', 'Categoria excluida com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Ocorreu um erro ao excluir a categoria: ' . $e->getMessage());
        }
    }
}
