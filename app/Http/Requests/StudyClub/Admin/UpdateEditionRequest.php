<?php

namespace App\Http\Requests\StudyClub\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEditionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $editionId = $this->route('id');

        return [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('studyclub_editions', 'number')->ignore($editionId),
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
            'number.unique' => 'Já existe outra edição com este número.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais que 255 caracteres.',
            'publish_date.required' => 'A data de publicação é obrigatória.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->boolean('status', true),
        ]);
    }
}
