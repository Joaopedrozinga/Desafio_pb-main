<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    /**
     * List tasks with optional filters.
     */
    public function index(IndexTaskRequest $request): JsonResponse
    {
        $tasks = $this->taskService->list($request->user(), $request->toDto());

        return response()->json([
            'data' => TaskResource::collection($tasks),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ]);
    }

    /**
     * Create a new task.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->user(), $request->toDto());

        return response()->json([
            'message' => 'Tarefa criada com sucesso.',
            'data' => new TaskResource($task),
        ], 201);
    }

    /**
     * Find task by ID and verify ownership.
     */
    private function findTask(Request $request, int $id): ?Task
    {
        return $request->user()->tasks()->find($id);
    }

    /**
     * Show a specific task.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $task = $this->findTask($request, $id);

        if ($task === null) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        return response()->json([
            'data' => new TaskResource($task),
        ]);
    }

    /**
     * Update the status of a task.
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = $this->findTask($request, $id);

        if ($task === null) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        $task = $this->taskService->updateStatus($task, $request->toDto());

        return response()->json([
            'message' => 'Tarefa actualizada com sucesso.',
            'data' => new TaskResource($task),
        ]);
    }

    /**
     * Delete a task.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $task = $this->findTask($request, $id);

        if ($task === null) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        $this->taskService->delete($task);

        return response()->json([
            'message' => 'Tarefa eliminada com sucesso.',
        ]);
    }
}
