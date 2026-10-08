<?php

namespace App\DTOs;

use App\Enums\TaskStatus;

final readonly class TaskFilterData
{
    public function __construct(
        public ?TaskStatus $status = null,
        public ?string $title = null,
        public ?int $userId = null,
    ) {}
}
