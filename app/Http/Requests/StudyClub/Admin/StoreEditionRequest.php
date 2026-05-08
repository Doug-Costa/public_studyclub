<?php

namespace App\Http\Requests\StudyClub\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEditionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Será controlado pelo middleware
    }

    public function rules(): array
    {
        return [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('studyclub_editions', 'number'),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'publish_date' => ['required', 'date', 'date_format:Y-m-d'],
            'status' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'O número da edição é obrigatório.',
            'number.integer' => 'O número deve ser um valor inteiro.',
            'number.min' => 'O número deve ser maior que zero.',
            'number.unique' => 'Já existe uma edição com este número.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais que 255 caracteres.',
            'publish_date.required' => 'A data de publicação é obrigatória.',
            'publish_date.date' => 'A data de publicação deve ser uma data válida.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->boolean('status', true),
        ]);
    }
}
