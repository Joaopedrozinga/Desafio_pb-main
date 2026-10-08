<?php

use App\DTOs\CreateTaskData;
use App\DTOs\TaskFilterData;
use App\DTOs\UpdateTaskStatusData;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Pagination\LengthAwarePaginator;

uses()->group('unit', 'services');

test('TaskService create creates task with pending status', function () {
    $user = User::factory()->create();
    $service = new TaskService;

    $data = new CreateTaskData(title: 'Test Task', description: 'Test Description');
    $task = $service->create($user, $data);

    expect($task)->toBeInstanceOf(Task::class);
    expect($task->title)->toBe('Test Task');
    expect($task->description)->toBe('Test Description');
    expect($task->status)->toBe(TaskStatus::Pending);
    expect($task->user_id)->toBe($user->id);
    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Test Task']);
});

test('TaskService create sets description to null when not provided', function () {
    $user = User::factory()->create();
    $service = new TaskService;

    $data = new CreateTaskData(title: 'Test Task');
    $task = $service->create($user, $data);

    expect($task->description)->toBeNull();
});

test('TaskService list returns paginated tasks for user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $service = new TaskService;

    $user->tasks()->create(['title' => 'User Task 1']);
    $user->tasks()->create(['title' => 'User Task 2']);
    $otherUser->tasks()->create(['title' => 'Other Task']);

    $filters = new TaskFilterData;
    $result = $service->list($user, $filters);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($result->total())->toBe(2);
    expect($result->items())->toHaveCount(2);
});

test('TaskService list filters by status', function () {
    $user = User::factory()->create();
    $service = new TaskService;

    $user->tasks()->create(['title' => 'Pending Task', 'status' => TaskStatus::Pending]);
    $user->tasks()->create(['title' => 'In Progress Task', 'status' => TaskStatus::InProgress]);
    $user->tasks()->create(['title' => 'Completed Task', 'status' => TaskStatus::Completed]);

    $filters = new TaskFilterData(status: TaskStatus::Pending);
    $result = $service->list($user, $filters);

    expect($result->total())->toBe(1);
    expect($result->first()->status)->toBe(TaskStatus::Pending);
});

test('TaskService list filters by title', function () {
    $user = User::factory()->create();
    $service = new TaskService;

    $user->tasks()->create(['title' => 'Important Task']);
    $user->tasks()->create(['title' => 'Regular Task']);

    $filters = new TaskFilterData(title: 'Important');
    $result = $service->list($user, $filters);

    expect($result->total())->toBe(1);
    expect($result->first()->title)->toBe('Important Task');
});

test('TaskService list filters by userId', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $service = new TaskService;

    $user->tasks()->create(['title' => 'User Task']);
    $otherUser->tasks()->create(['title' => 'Other Task']);

    $filters = new TaskFilterData(userId: $otherUser->id);
    $result = $service->list($user, $filters);

    // The service should filter by user_id, but user can only see their own tasks
    // So this will return 0 because we're scoping to $user but filtering for $otherUser
    expect($result->total())->toBe(0);
});

test('TaskService updateStatus updates task status', function () {
    $user = User::factory()->create();
    $service = new TaskService;

    $task = $user->tasks()->create(['title' => 'Test Task', 'status' => TaskStatus::Pending]);

    $data = new UpdateTaskStatusData(status: TaskStatus::Completed);
    $updatedTask = $service->updateStatus($task, $data);

    expect($updatedTask->status)->toBe(TaskStatus::Completed);
    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
});

test('TaskService delete removes task', function () {
    $user = User::factory()->create();
    $service = new TaskService;

    $task = $user->tasks()->create(['title' => 'Test Task']);

    $service->delete($task);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});
