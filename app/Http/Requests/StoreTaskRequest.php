<?php

namespace App\Http\Requests;

use App\DTOs\CreateTaskData;
use Illuminate\Foundation\Http\FormRequest;

final class StoreTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Build the DTO from the validated data.
     */
    public function toDto(): CreateTaskData
    {
        return new CreateTaskData(
            title: $this->validated('title'),
            description: $this->validated('description'),
        );
    }
}
