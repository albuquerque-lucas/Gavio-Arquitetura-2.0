<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'cover_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string|max:255',
            'ownership' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'username' => 'username',
            'email' => 'email',
            'password' => 'senha',
            'cover_path' => 'imagem de perfil',
            'description' => 'descricao',
            'ownership' => 'ownership',
        ];
    }
}
