<?php

use App\Enums\TaskStatus;
use App\Models\User;

uses()->group('api', 'tasks');

test('create valid task returns 201', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->postJson('/api/v1/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data' => ['id', 'title', 'description', 'status', 'status_label', 'created_at', 'updated_at'],
        ])
        ->assertJsonFragment(['message' => 'Tarefa criada com sucesso.']);
});

test('create task without title returns 422', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->postJson('/api/v1/tasks', [
            'description' => 'Test Description',
        ]);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['title']])
        ->assertJsonFragment(['message' => 'The title field is required.']);
});

test('create task requires authentication', function () {
    $response = $this->postJson('/api/v1/tasks', [
        'title' => 'Test Task',
    ]);

    $response->assertStatus(401);
});

test('list tasks returns only authenticated user tasks', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $user->tasks()->create(['title' => 'User Task 1', 'description' => 'Desc 1']);
    $user->tasks()->create(['title' => 'User Task 2', 'description' => 'Desc 2']);
    $otherUser->tasks()->create(['title' => 'Other Task', 'description' => 'Desc 3']);

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->getJson('/api/v1/tasks');

    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'meta'])
        ->assertJsonCount(2, 'data');
});

test('list tasks filters by status', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $user->tasks()->create(['title' => 'Pending Task', 'status' => TaskStatus::Pending]);
    $user->tasks()->create(['title' => 'In Progress Task', 'status' => TaskStatus::InProgress]);
    $user->tasks()->create(['title' => 'Completed Task', 'status' => TaskStatus::Completed]);

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->getJson('/api/v1/tasks?status=pending');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['status' => 'pending']);
});

test('show task returns 200 for owned task', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $task = $user->tasks()->create(['title' => 'Test Task']);

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->getJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => ['id', 'title', 'status']]);
});

test('show task returns 404 for non-existent task', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->getJson('/api/v1/tasks/999');

    $response->assertStatus(404)
        ->assertJsonFragment(['message' => 'Task not found.']);
});

test('show task returns 404 for other user task', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $task = $otherUser->tasks()->create(['title' => 'Other Task']);

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->getJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(404)
        ->assertJsonFragment(['message' => 'Task not found.']);
});

test('update task status returns 200 for valid status', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $task = $user->tasks()->create(['title' => 'Test Task', 'status' => TaskStatus::Pending]);

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->putJson("/api/v1/tasks/{$task->id}", ['status' => 'in_progress']);

    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Tarefa actualizada com sucesso.', 'status' => 'in_progress']);
});

test('update task status returns 422 for invalid status', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $task = $user->tasks()->create(['title' => 'Test Task']);

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->putJson("/api/v1/tasks/{$task->id}", ['status' => 'invalid']);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors' => ['status']]);
});

test('update task status returns 404 for non-existent task', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->putJson('/api/v1/tasks/999', ['status' => 'in_progress']);

    $response->assertStatus(404)
        ->assertJsonFragment(['message' => 'Task not found.']);
});

test('delete task returns 200 for owned task', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $task = $user->tasks()->create(['title' => 'Test Task']);

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->deleteJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Tarefa eliminada com sucesso.']);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('delete task returns 404 for non-existent task', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->deleteJson('/api/v1/tasks/999');

    $response->assertStatus(404)
        ->assertJsonFragment(['message' => 'Task not found.']);
});
