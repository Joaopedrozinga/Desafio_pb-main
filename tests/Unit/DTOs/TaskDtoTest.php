<?php

use App\DTOs\CreateTaskData;
use App\DTOs\TaskFilterData;
use App\DTOs\UpdateTaskStatusData;
use App\Enums\TaskStatus;

uses()->group('unit', 'dtos');

test('CreateTaskData constructs with required title and optional description', function () {
    $dto = new CreateTaskData(title: 'Test Task', description: 'Test Description');

    expect($dto->title)->toBe('Test Task');
    expect($dto->description)->toBe('Test Description');
});

test('CreateTaskData constructs with only title', function () {
    $dto = new CreateTaskData(title: 'Test Task');

    expect($dto->title)->toBe('Test Task');
    expect($dto->description)->toBeNull();
});

test('UpdateTaskStatusData constructs with TaskStatus enum', function () {
    $dto = new UpdateTaskStatusData(status: TaskStatus::InProgress);

    expect($dto->status)->toBe(TaskStatus::InProgress);
    expect($dto->status->value)->toBe('in_progress');
});

test('TaskFilterData constructs with all optional fields', function () {
    $dto = new TaskFilterData(
        status: TaskStatus::Pending,
        title: 'search term',
        userId: 1
    );

    expect($dto->status)->toBe(TaskStatus::Pending);
    expect($dto->title)->toBe('search term');
    expect($dto->userId)->toBe(1);
});

test('TaskFilterData constructs with all null fields', function () {
    $dto = new TaskFilterData;

    expect($dto->status)->toBeNull();
    expect($dto->title)->toBeNull();
    expect($dto->userId)->toBeNull();
});

test('StoreTaskRequest toDto returns CreateTaskData', function () {
    $dto = new CreateTaskData(
        title: 'Test Task',
        description: 'Test Description',
    );

    expect($dto)->toBeInstanceOf(CreateTaskData::class);
    expect($dto->title)->toBe('Test Task');
    expect($dto->description)->toBe('Test Description');
});

test('UpdateTaskRequest toDto returns UpdateTaskStatusData', function () {
    $status = 'in_progress';

    $dto = new UpdateTaskStatusData(
        status: TaskStatus::from($status),
    );

    expect($dto)->toBeInstanceOf(UpdateTaskStatusData::class);
    expect($dto->status)->toBe(TaskStatus::InProgress);
});

test('IndexTaskRequest toDto returns TaskFilterData with status', function () {
    $status = 'pending';

    $dto = new TaskFilterData(
        status: TaskStatus::from($status),
        title: null,
        userId: null,
    );

    expect($dto)->toBeInstanceOf(TaskFilterData::class);
    expect($dto->status)->toBe(TaskStatus::Pending);
    expect($dto->title)->toBeNull();
    expect($dto->userId)->toBeNull();
});

test('IndexTaskRequest toDto returns TaskFilterData with all filters', function () {
    $dto = new TaskFilterData(
        status: TaskStatus::Completed,
        title: 'search',
        userId: 5,
    );

    expect($dto->status)->toBe(TaskStatus::Completed);
    expect($dto->title)->toBe('search');
    expect($dto->userId)->toBe(5);
});

test('IndexTaskRequest toDto returns TaskFilterData with all null when no filters', function () {
    $dto = new TaskFilterData(
        status: null,
        title: null,
        userId: null,
    );

    expect($dto->status)->toBeNull();
    expect($dto->title)->toBeNull();
    expect($dto->userId)->toBeNull();
});
