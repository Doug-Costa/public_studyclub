<?php

namespace App\Http\Requests\StudyClub\Admin;

use App\Models\StudyClubItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:' . implode(',', StudyClubItem::TYPES)],
            'type_label' => ['required', 'string', 'max:100'],
            'author' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'resumo' => ['required', 'string'],
            'achados' => ['required', 'string'],
            'implicacoes' => ['required', 'string'],
            'external_url' => ['required', 'url', 'max:500'],
            'icon' => ['required', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:102400'],
            'likes' => ['integer', 'min:0'],
            'comments' => ['integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'A categoria é obrigatória.',
            'type.required' => 'O tipo de conteúdo é obrigatório.',
            'type.in' => 'O tipo selecionado é inválido.',
            'type_label.required' => 'O rótulo do tipo é obrigatório.',
            'author.required' => 'O autor é obrigatório.',
            'title.required' => 'O título é obrigatório.',
            'resumo.required' => 'O resumo é obrigatório.',
            'achados.required' => 'Os achados são obrigatórios.',
            'implicacoes.required' => 'As implicações clínicas são obrigatórias.',
            'external_url.required' => 'A URL externa é obrigatória.',
            'external_url.url' => 'A URL deve ser um endereço válido.',
            'icon.required' => 'O ícone é obrigatório.',
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser JPG, PNG, GIF ou WebP.',
            'image.max' => 'A imagem não pode ter mais que 100MB.',
        ];
    }
}
