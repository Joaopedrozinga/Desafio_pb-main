<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Task Manager API',
    version: '1.0.0',
    description: 'RESTful API for task management (To-Do List).',
    contact: new OA\Contact(name: 'Pacheco Barroso'),
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Provide the Sanctum API token as a Bearer token.'
)]
#[OA\Components(
    schemas: [
        new OA\Schema(
            schema: 'User',
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                new OA\Property(property: 'email', type: 'string', example: 'test@example.com'),
                new OA\Property(property: 'email_verified_at', type: 'string', format: 'date-time', nullable: true, example: null),
                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2025-01-01T00:00:00Z'),
                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2025-01-01T00:00:00Z'),
            ],
        ),
        new OA\Schema(
            schema: 'Task',
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'title', type: 'string', example: 'Entrevista'),
                new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Falar sobre o projecto'),
                new OA\Property(property: 'status', type: 'string', enum: ['pending', 'in_progress', 'completed'], example: 'pending'),
                new OA\Property(property: 'status_label', type: 'string', example: 'Pendente'),
                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2025-01-01T00:00:00Z'),
                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2025-01-01T00:00:00Z'),
            ],
        ),
        new OA\Schema(
            schema: 'RegisterRequest',
            type: 'object',
            required: ['name', 'email', 'password'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'test@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password'),
            ],
        ),
        new OA\Schema(
            schema: 'LoginRequest',
            type: 'object',
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'test@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
            ],
        ),
        new OA\Schema(
            schema: 'StoreTaskRequest',
            type: 'object',
            required: ['title'],
            properties: [
                new OA\Property(property: 'title', type: 'string', example: 'Nova Tarefa'),
                new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Descrição detalhada'),
            ],
        ),
        new OA\Schema(
            schema: 'UpdateTaskRequest',
            type: 'object',
            required: ['status'],
            properties: [
                new OA\Property(property: 'status', type: 'string', enum: ['pending', 'in_progress', 'completed'], example: 'in_progress'),
            ],
        ),
        new OA\Schema(
            schema: 'ValidationError',
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                new OA\Property(property: 'errors', type: 'object', additionalProperties: new OA\AdditionalProperties(
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                )),
            ],
        ),
        new OA\Schema(
            schema: 'NotFoundErrorResponse',
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Task not found.'),
            ],
        ),
        new OA\Schema(
            schema: 'UnauthorizedResponse',
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
            ],
        ),
    ],
)]
#[OA\Get(
    path: '/api/v1/user',
    summary: 'Get authenticated user',
    description: 'Retrieve the currently authenticated user\'s profile.',
    tags: ['Authentication'],
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'User profile',
            content: new OA\JsonContent(ref: '#/components/schemas/User'),
        ),
        new OA\Response(
            response: 401,
            description: 'Unauthenticated',
            content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse'),
        ),
    ],
)]
final class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    #[OA\Post(
        path: '/api/v1/register',
        summary: 'Register a new user',
        description: 'Create a new user account and issue an API token.',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest'),
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Utilizador registado com sucesso.'),
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                            new OA\Property(property: 'token', type: 'string', example: '4|mediumtokenstring'),
                        ]),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
            ),
        ],
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->toDto());

        return response()->json([
            'message' => 'Utilizador registado com sucesso.',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ], 201);
    }

    #[OA\Post(
        path: '/api/v1/login',
        summary: 'Authenticate user and issue API token',
        description: 'Log in with email and password to receive a Sanctum API token.',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest'),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authentication successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Autenticado com sucesso.'),
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                            new OA\Property(property: 'token', type: 'string', example: '4|mediumtokenstring'),
                        ]),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Credenciais inválidas.'),
                    ],
                    type: 'object',
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError'),
            ),
        ],
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->toDto());

        if ($result === null) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        return response()->json([
            'message' => 'Autenticado com sucesso.',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/v1/logout',
        summary: 'Revoke the current API token',
        description: 'Log out the authenticated user by revoking their API token.',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Session terminated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Sessão terminada com sucesso.'),
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
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Sessão terminada com sucesso.',
        ]);
    }
}
