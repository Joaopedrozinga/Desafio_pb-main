<?php

namespace App\Http\OpenApi;

/**
 * Centralized OpenAPI definitions.
 *
 * @OA\Info(
 *   title="Task Manager API",
 *   version="1.0.0",
 *   description="RESTful API for task management (To-Do List).",
 *
 *   @OA\Contact(
 *     name="Pacheco Barroso",
 *   ),
 * )
 *
 * @OA\Get(
 *   path="/api/v1/user",
 *   summary="Get authenticated user",
 *   description="Retrieve the currently authenticated user's profile.",
 *   tags={"Authentication"},
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Response(
 *     response=200,
 *     description="User profile",
 *
 *     @OA\JsonContent(ref="#/components/schemas/User"),
 *   ),
 *
 *   @OA\Response(
 *     response=401,
 *     description="Unauthenticated",
 *
 *     @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
 *   ),
 * )
 *
 * @OA\SecurityScheme(
 *   scheme="bearer",
 *   bearerFormat="JWT",
 *   type="http",
 *   securityScheme="bearerAuth",
 *   description="Provide the Sanctum API token as a Bearer token."
 * )
 *
 * @OA\Schema(
 *   schema="User",
 *
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="John Doe"),
 *   @OA\Property(property="email", type="string", example="test@example.com"),
 *   @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example=null),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 * )
 *
 * @OA\Schema(
 *   schema="Task",
 *
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="title", type="string", example="Entrevista"),
 *   @OA\Property(property="description", type="string", nullable=true, example="Falar sobre o projecto"),
 *   @OA\Property(property="status", type="string", enum={"pending","in_progress","completed"}, example="pending"),
 *   @OA\Property(property="status_label", type="string", example="Pendente"),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
 * )
 *
 * @OA\Schema(
 *   schema="RegisterRequest",
 *
 *   @OA\Property(property="name", type="string", example="John Doe"),
 *   @OA\Property(property="email", type="string", format="email", example="test@example.com"),
 *   @OA\Property(property="password", type="string", format="password", example="password"),
 *   @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
 * )
 *
 * @OA\Schema(
 *   schema="LoginRequest",
 *
 *   @OA\Property(property="email", type="string", format="email", example="test@example.com"),
 *   @OA\Property(property="password", type="string", format="password", example="password"),
 * )
 *
 * @OA\Schema(
 *   schema="StoreTaskRequest",
 *
 *   @OA\Property(property="title", type="string", example="Nova Tarefa"),
 *   @OA\Property(property="description", type="string", nullable=true, example="Descrição detalhada"),
 * )
 *
 * @OA\Schema(
 *   schema="UpdateTaskRequest",
 *
 *   @OA\Property(property="status", type="string", enum={"pending","in_progress","completed"}, example="in_progress"),
 * )
 *
 * @OA\Schema(
 *   schema="ValidationError",
 *
 *   @OA\Property(property="message", type="string", example="The given data was invalid."),
 *   @OA\Property(
 *     property="errors",
 *     type="object",
 *
 *     @OA\AdditionalProperties(
 *         type="array",
 *
 *         @OA\Items(type="string")
 *     )
 *   ),
 * )
 *
 * @OA\Schema(
 *   schema="NotFoundErrorResponse",
 *
 *   @OA\Property(property="message", type="string", example="Task not found."),
 * )
 *
 * @OA\Schema(
 *   schema="UnauthorizedResponse",
 *
 *   @OA\Property(property="message", type="string", example="Unauthenticated."),
 * )
 */
final class OpenApiDefinitions {}
