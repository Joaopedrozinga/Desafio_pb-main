<?php

namespace App\Services;

use App\DTOs\CreateTaskData;
use App\DTOs\TaskFilterData;
use App\DTOs\UpdateTaskStatusData;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class TaskService
{
    /**
     * Create a new task for the user.
     */
    public function create(User $user, CreateTaskData $data): Task
    {
        return $user->tasks()->create([
            'title' => $data->title,
            'description' => $data->description,
            'status' => TaskStatus::Pending,
        ]);
    }

    /**
     * List tasks for the user with optional filters.
     */
    public function list(User $user, TaskFilterData $filters): LengthAwarePaginator
    {
        $query = $user->tasks();

        if ($filters->status !== null) {
            $query->where('status', $filters->status);
        }

        if ($filters->title !== null) {
            $query->where('title', 'like', "%{$filters->title}%");
        }

        if ($filters->userId !== null) {
            $query->where('user_id', $filters->userId);
        }

        return $query->latest()->paginate(15);
    }

    /**
     * Update the status of a task.
     */
    public function updateStatus(Task $task, UpdateTaskStatusData $data): Task
    {
        $task->update([
            'status' => $data->status,
        ]);

        return $task->fresh();
    }

    /**
     * Delete a task.
     */
    public function delete(Task $task): void
    {
        $task->delete();
    }
}
