<?php

namespace App\Livewire\Forms;

use App\DTOs\CreateTaskData;
use Livewire\Form;

class TaskForm extends Form
{
    public string $title = '';

    public string $description = '';

    /**
     * Reset the form fields.
     */
    public function resetForm(): void
    {
        $this->reset(['title', 'description']);
        $this->resetValidation();
    }

    /**
     * Convert form data to DTO.
     */
    public function toDto(): CreateTaskData
    {
        return new CreateTaskData(
            title: $this->title,
            description: $this->description ?: null,
        );
    }
}
