<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserCoverService;
use Exception;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderByDesc('id')->paginate();

        return view('admin-users.index', compact('users'));
    }

    public function create()
    {
        return view('admin-users.create');
    }

    public function store(StoreUserRequest $request, CreateUserAction $createUser)
    {
        try {
            $createUser(
                $request->validated(),
                $request->boolean('ownership'),
                $request->file('cover_path')
            );

            return redirect()->route('admin.users.index')->with('success', 'Usuario criado com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar o usuario. Tente novamente.');
        }
    }

    public function edit(User $user)
    {
        return view('admin-users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $updateUser)
    {
        try {
            $updateUser(
                $user,
                $request->validated(),
                $request->boolean('ownership'),
                $request->file('cover_path')
            );

            return redirect()->route('admin.users.edit', $user)->with('success', 'Usuario atualizado com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar o usuario. Tente novamente.');
        }
    }

    public function destroy(User $user, UserCoverService $coverService)
    {
        try {
            $coverService->delete($user);
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Usuario excluido com sucesso!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('admin.users.index')->with('error', 'Erro ao excluir o usuario. Tente novamente.');
        }
    }
}
