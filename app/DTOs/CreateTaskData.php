<?php

namespace App\DTOs;

final readonly class CreateTaskData
{
    public function __construct(
        public string $title,
        public ?string $description = null,
    ) {}
}
