<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * Register a new user.
     *
     * @OA\Post(
     *   path="/api/v1/register",
     *   summary="Register a new user",
     *   description="Create a new user account and issue an API token.",
     *   tags={"Authentication"},
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(ref="#/components/schemas/RegisterRequest"),
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="User registered successfully",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Utilizador registado com sucesso."),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="user", ref="#/components/schemas/User"),
     *         @OA\Property(property="token", type="string", example="4|mediumtokenstring"),
     *       ),
     *     ),
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

    /**
     * Authenticate a user and return an API token.
     *
     * @OA\Post(
     *   path="/api/v1/login",
     *   summary="Authenticate user and issue API token",
     *   description="Log in with email and password to receive a Sanctum API token.",
     *   tags={"Authentication"},
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(ref="#/components/schemas/LoginRequest"),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Authentication successful",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Autenticado com sucesso."),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="user", ref="#/components/schemas/User"),
     *         @OA\Property(property="token", type="string", example="4|mediumtokenstring"),
     *       ),
     *     ),
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Invalid credentials",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Credenciais inválidas."),
     *     ),
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

    /**
     * Revoke the current API token.
     *
     * @OA\Post(
     *   path="/api/v1/logout",
     *   summary="Revoke the current API token",
     *   description="Log out the authenticated user by revoking their API token.",
     *   tags={"Authentication"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Session terminated successfully",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Sessão terminada com sucesso."),
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
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Sessão terminada com sucesso.',
        ]);
    }
}
