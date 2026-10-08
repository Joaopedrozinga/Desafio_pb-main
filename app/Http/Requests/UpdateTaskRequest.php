<?php

namespace App\Http\Requests;

use App\DTOs\UpdateTaskStatusData;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:'.implode(',', array_column(TaskStatus::cases(), 'value'))],
        ];
    }

    /**
     * Build the DTO from the validated data.
     */
    public function toDto(): UpdateTaskStatusData
    {
        return new UpdateTaskStatusData(
            status: TaskStatus::from($this->validated('status')),
        );
    }
}
