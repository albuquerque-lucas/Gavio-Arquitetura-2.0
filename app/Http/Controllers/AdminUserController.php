<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cover_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'description' => 'nullable|string|max:255',
                'ownership' => 'nullable|boolean',
            ]);

            $user = new User();
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->description = $validated['description'] ?? null;
            $user->ownership = $request->boolean('ownership');

            if ($request->hasFile('cover_path')) {
                $file = $request->file('cover_path');
                $imagePath = $file->store('users/covers', 'public');
                $user->cover_path = '/storage/' . $imagePath;
                $user->cover_filename = $file->getClientOriginalName();
            }

            $user->save();

            return redirect()->route('admin.users.index')->with('success', 'Usuario criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validacao. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar o usuario: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        try {
            return view('admin-users.edit', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Usuario nao encontrado.');
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'cover_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'ownership' => 'nullable|boolean',
                'description' => 'nullable|string',
            ]);

            $validated['ownership'] = $request->boolean('ownership');

            $user->name = $validated['name'];
            $user->username = $validated['username'];
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

            return redirect()->route('admin.users.edit', $user)->with('success', 'Usuario atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Erro de validacao. Por favor, verifique os dados inseridos.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o usuario: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->cover_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->cover_path));
            }
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Usuario excluido com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Erro ao excluir o usuario: ' . $e->getMessage());
        }
    }
}
