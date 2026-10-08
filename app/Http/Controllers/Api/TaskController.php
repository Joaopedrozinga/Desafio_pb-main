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

/**
 * @OA\Schema(
 *   schema="User",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="John Doe"),
 *   @OA\Property(property="email", type="string", example="test@example.com"),
 *   @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example=null),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 * )
 * @OA\Schema(
 *   schema="Task",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="title", type="string", example="Entrevista"),
 *   @OA\Property(property="description", type="string", nullable=true, example="Falar sobre o projecto"),
 *   @OA\Property(property="status", type="string", enum={"pending","in_progress","completed"}, example="pending"),
 *   @OA\Property(property="status_label", type="string", example="Pendente"),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 * )
 * @OA\Schema(
 *   schema="RegisterRequest",
 *   @OA\Property(property="name", type="string", example="John Doe"),
 *   @OA\Property(property="email", type="string", format="email", example="test@example.com"),
 *   @OA\Property(property="password", type="string", format="password", example="password"),
 *   @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
 * )
 * @OA\Schema(
 *   schema="LoginRequest",
 *   @OA\Property(property="email", type="string", format="email", example="test@example.com"),
 *   @OA\Property(property="password", type="string", format="password", example="password"),
 * )
 * @OA\Schema(
 *   schema="StoreTaskRequest",
 *   @OA\Property(property="title", type="string", example="Nova Tarefa"),
 *   @OA\Property(property="description", type="string", nullable=true, example="Descrição detalhada"),
 * )
 * @OA\Schema(
 *   schema="UpdateTaskRequest",
 *   @OA\Property(property="status", type="string", enum={"pending","in_progress","completed"}, example="in_progress"),
 * )
 * @OA\Schema(
 *   schema="ValidationError",
 *   @OA\Property(property="message", type="string", example="The given data was invalid."),
 *   @OA\Property(
 *     property="errors",
 *     type="object",
 *     @OA\AdditionalProperties(type="array", @OA\Items(type="string")),
 *   ),
 * )
 * @OA\Schema(
 *   schema="NotFoundErrorResponse",
 *   @OA\Property(property="message", type="string", example="Task not found."),
 * )
 * @OA\Schema(
 *   schema="UnauthorizedResponse",
 *   @OA\Property(property="message", type="string", example="Unauthenticated."),
 * )
 */
final class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    /**
     * List tasks with optional filters.
     *
     * @OA\Get(
     *   path="/api/v1/tasks",
     *   summary="List tasks",
     *   description="Retrieve a paginated list of the authenticated user's tasks, with optional filters.",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="Filter by task status (pending, in_progress, completed)",
     *
     *     @OA\Schema(type="string", enum={"pending","in_progress","completed"}),
     *   ),
     *
     *   @OA\Parameter(
     *     name="title",
     *     in="query",
     *     description="Filter by title (partial match)",
     *
     *     @OA\Schema(type="string"),
     *   ),
     *
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="Filter by user ID",
     *
     *     @OA\Schema(type="integer"),
     *   ),
     *
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Page number for pagination",
     *
     *     @OA\Schema(type="integer", default=1),
     *   ),
     *
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     description="Number of items per page",
     *
     *     @OA\Schema(type="integer", default=15),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="List of tasks",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Task")),
     *       @OA\Property(
     *         property="meta",
     *         type="object",
     *         @OA\Property(property="current_page", type="integer", example=1),
     *         @OA\Property(property="last_page", type="integer", example=5),
     *         @OA\Property(property="per_page", type="integer", example=15),
     *         @OA\Property(property="total", type="integer", example=75),
     *       ),
     *     ),
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *
     *     @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *   ),
     * )
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
     *
     * @OA\Post(
     *   path="/api/v1/tasks",
     *   summary="Create a new task",
     *   description="Create a new task for the authenticated user.",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(ref="#/components/schemas/StoreTaskRequest"),
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Task created successfully",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Tarefa criada com sucesso."),
     *       @OA\Property(property="data", ref="#/components/schemas/Task"),
     *     ),
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *
     *     @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *   ),
     *
     *   @OA\Response(
     *     response=422,
     *     description="Validation error",
     *
     *     @OA\JsonContent(ref="#/components/schemas/ValidationError"),
     *   ),
     * )
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
     * Show a specific task.
     *
     * @OA\Get(
     *   path="/api/v1/tasks/{id}",
     *   summary="Show a task",
     *   description="Retrieve a specific task by ID. The task must belong to the authenticated user.",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *
     *     @OA\Schema(type="integer", example=1),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Task details",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="data", ref="#/components/schemas/Task"),
     *     ),
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *
     *     @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Task not found",
     *
     *     @OA\JsonContent(ref="#/components/schemas/NotFoundErrorResponse"),
     *   ),
     * )
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
     *
     * @OA\Put(
     *   path="/api/v1/tasks/{id}",
     *   summary="Update task status",
     *   description="Update the status of an existing task. The task must belong to the authenticated user.",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *
     *     @OA\Schema(type="integer", example=1),
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest"),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Task updated successfully",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Tarefa actualizada com sucesso."),
     *       @OA\Property(property="data", ref="#/components/schemas/Task"),
     *     ),
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *
     *     @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Task not found",
     *
     *     @OA\JsonContent(ref="#/components/schemas/NotFoundErrorResponse"),
     *   ),
     *
     *   @OA\Response(
     *     response=422,
     *     description="Validation error",
     *
     *     @OA\JsonContent(ref="#/components/schemas/ValidationError"),
     *   ),
     * )
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
     *
     * @OA\Delete(
     *   path="/api/v1/tasks/{id}",
     *   summary="Delete a task",
     *   description="Delete an existing task. The task must belong to the authenticated user.",
     *   tags={"Tasks"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *
     *     @OA\Schema(type="integer", example=1),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Task deleted successfully",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Tarefa eliminada com sucesso."),
     *     ),
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *
     *     @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Task not found",
     *
     *     @OA\JsonContent(ref="#/components/schemas/NotFoundErrorResponse"),
     *   ),
     * )
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
