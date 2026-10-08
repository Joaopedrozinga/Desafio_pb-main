<?php

namespace App\Http\Requests;

use App\DTOs\TaskFilterData;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

final class IndexTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'string', 'in:'.implode(',', array_column(TaskStatus::cases(), 'value'))],
            'title' => ['sometimes', 'string', 'max:255'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Build the DTO from the validated data.
     */
    public function toDto(): TaskFilterData
    {
        $status = $this->validated('status');

        return new TaskFilterData(
            status: $status ? TaskStatus::from($status) : null,
            title: $this->validated('title'),
            userId: $this->validated('user_id'),
        );
    }
}
