<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate();
        return view('admin-users.index', compact('users'));
    }

    public function create()
    {
        return view('admin-users.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cover_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'description' => 'nullable|string|max:255',
            ]);

            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->description = $validated['description'] ?? null;

            if ($request->hasFile('cover_path')) {
                $file = $request->file('cover_path');
                $imagePath = $file->store('users/covers', 'public');
                $user->cover_path = '/storage/' . $imagePath;
                $user->cover_filename = $file->getClientOriginalName();
            }

            $user->save();

            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validação. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar o usuário: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin-users.edit', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Usuário não encontrado.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
                'cover_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'ownership' => 'nullable|boolean',
                'description' => 'nullable|string',
            ]);

            $validated['ownership'] = $request->boolean('ownership');

            $user = User::findOrFail($id);
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->description = $validated['description'] ?? null;
            $user->ownership = $validated['ownership'];

            if ($request->hasFile('cover_path')) {
                if ($user->cover_path) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $user->cover_path));
                }
                $file = $request->file('cover_path');
                $imagePath = $file->store('users/covers', 'public');
                $user->cover_path = '/storage/' . $imagePath;
                $user->cover_filename = $file->getClientOriginalName();
            }

            $user->save();

            return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validação. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o usuário: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->cover_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->cover_path));
            }
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Erro ao excluir o usuário: ' . $e->getMessage());
        }
    }
}
