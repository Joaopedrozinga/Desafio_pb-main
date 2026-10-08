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
use OpenApi\Attributes as OA;

final class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    #[OA\Get(
        path: '/api/v1/tasks',
        summary: 'List tasks',
        description: 'Retrieve a paginated list of the authenticated user\'s tasks, with optional filters.',
        tags: ['Tasks'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'status',
                in: 'query',
                description: 'Filter by task status (pending, in_progress, completed)',
                schema: new OA\Schema(type: 'string', enum: ['pending', 'in_progress', 'completed']),
            ),
            new OA\Parameter(
                name: 'title',
                in: 'query',
                description: 'Filter by title (partial match)',
                schema: new OA\Schema(type: 'string'),
            ),
            new OA\Parameter(
                name: 'user_id',
                in: 'query',
                description: 'Filter by user ID',
                schema: new OA\Schema(type: 'integer'),
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Page number for pagination',
                schema: new OA\Schema(type: 'integer', default: '1'),
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Number of items per page',
                schema: new OA\Schema(type: 'integer', default: '15'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tasks',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Task')),
                        new OA\Property(property: 'meta', type: 'object', properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'last_page', type: 'integer', example: 5),
                            new OA\Property(property: 'per_page', type: 'integer', example: 15),
                            new OA\Property(property: 'total', type: 'integer', example: 75),
                        ]),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse'),
            ),
        ],
    )]
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

    #[OA\Post(
        path: '/api/v1/tasks',
        summary: 'Create a new task',
        description: 'Create a new task for the authenticated user.',
        tags: ['Tasks'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreTaskRequest'),
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Task created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Tarefa criada com sucesso.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Task'),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse'),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
            ),
        ],
    )]
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->user(), $request->toDto());

        return response()->json([
            'message' => 'Tarefa criada com sucesso.',
            'data' => new TaskResource($task),
        ], 201);
    }

    #[OA\Get(
        path: '/api/v1/tasks/{id}',
        summary: 'Show a task',
        description: 'Retrieve a specific task by ID. The task must belong to the authenticated user.',
        tags: ['Tasks'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Task'),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse'),
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundErrorResponse'),
            ),
        ],
    )]
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

    #[OA\Put(
        path: '/api/v1/tasks/{id}',
        summary: 'Update task status',
        description: 'Update the status of an existing task. The task must belong to the authenticated user.',
        tags: ['Tasks'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateTaskRequest'),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Tarefa actualizada com sucesso.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Task'),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse'),
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundErrorResponse'),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
            ),
        ],
    )]
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

    #[OA\Delete(
        path: '/api/v1/tasks/{id}',
        summary: 'Delete a task',
        description: 'Delete an existing task. The task must belong to the authenticated user.',
        tags: ['Tasks'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Tarefa eliminada com sucesso.'),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse'),
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundErrorResponse'),
            ),
        ],
    )]
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

    private function findTask(Request $request, int $id): ?Task
    {
        return $request->user()->tasks()->find($id);
    }
}
