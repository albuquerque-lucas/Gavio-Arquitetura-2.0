<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxCharacterCount;

class ProjectUpdateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', new MaxCharacterCount(255)],
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:50000',
            'location' => ['nullable', 'string', new MaxCharacterCount(255)],
            'area' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|boolean',
            'description' => ['nullable', 'string', new MaxCharacterCount(250)],
            'year' => 'nullable|integer',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'título',
            'cover' => 'capa',
            'location' => 'localização',
            'area' => 'área',
            'category_id' => 'categoria',
            'status' => 'status',
            'description' => 'descrição',
            'year' => 'ano',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser uma string.',
            'max' => 'O campo :attribute não deve ser maior que :max caracteres.',
            'image' => 'O campo :attribute deve ser uma imagem.',
            'mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'exists' => 'O campo :attribute selecionado é inválido.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
        ];
    }
}
