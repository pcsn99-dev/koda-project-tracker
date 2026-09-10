<?php

namespace App\Http\Requests;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::enum(ProjectStatus::class)],
            'priority' => ['nullable', Rule::enum(ProjectPriority::class)],
            'sort_by' => [
                'nullable',
                Rule::in([
                    'client_name',
                    'project_name',
                    'start_date',
                    'due_date',
                    'created_at',
                ]),
            ],
            'sort_direction' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}