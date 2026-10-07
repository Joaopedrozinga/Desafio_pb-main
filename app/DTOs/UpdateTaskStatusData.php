<?php

namespace App\DTOs;

use App\Enums\TaskStatus;

final readonly class UpdateTaskStatusData
{
    public function __construct(
        public TaskStatus $status,
    ) {}
}