<?php

namespace App\Livewire;

use App\DTOs\TaskFilterData;
use App\DTOs\UpdateTaskStatusData;
use App\Enums\TaskStatus;
use App\Livewire\Forms\TaskForm;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Minhas Tarefas')]
class TaskList extends Component
{
    use WithPagination;

    public TaskForm $form;

    // Filtros
    public ?string $filterStatus = null;

    public ?string $filterTitle = null;

    public ?int $filterUserId = null;

    // Modal de confirmação para apagar
    public bool $showDeleteModal = false;

    public ?int $taskIdToDelete = null;

    // Contadores para estatísticas
    public int $totalTasks = 0;

    public int $pendingTasks = 0;

    public int $inProgressTasks = 0;

    public int $completedTasks = 0;

    protected function getListeners(): array
    {
        return [
            'taskCreated' => 'refreshTasks',
            'taskUpdated' => 'refreshTasks',
            'taskDeleted' => 'refreshTasks',
        ];
    }

    public function mount(): void
    {
        $this->form = new TaskForm($this, 'form');
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $user = auth()->user();
        $this->totalTasks = $user->tasks()->count();
        $this->pendingTasks = $user->tasks()->where('status', TaskStatus::Pending)->count();
        $this->inProgressTasks = $user->tasks()->where('status', TaskStatus::InProgress)->count();
        $this->completedTasks = $user->tasks()->where('status', TaskStatus::Completed)->count();
    }

    #[Computed]
    public function filters(): TaskFilterData
    {
        return new TaskFilterData(
            status: $this->filterStatus ? TaskStatus::from($this->filterStatus) : null,
            title: $this->filterTitle ?: null,
            userId: $this->filterUserId,
        );
    }

    #[Computed]
    public function tasks(): LengthAwarePaginator
    {
        return app(TaskService::class)->list(auth()->user(), $this->filters());
    }

    #[Computed]
    public function users(): Collection
    {
        return User::orderBy('name')->get();
    }

    public function createTask(): void
    {
        $this->validate([
            'form.title' => 'required|string|max:255',
            'form.description' => 'nullable|string',
        ]);

        app(TaskService::class)->create(auth()->user(), $this->form->toDto());

        $this->form->resetForm();
        $this->dispatch('modal-close', name: 'create-task-modal');
        $this->dispatch('taskCreated');
        $this->loadStats();

        $this->dispatch('toast', text: 'Tarefa criada com sucesso.', variant: 'success');
    }

    public function updateTaskStatus(int $taskId, string $status): void
    {
        $task = auth()->user()->tasks()->find($taskId);

        if (! $task) {
            $this->dispatch('toast', text: 'Tarefa não encontrada.', variant: 'danger');

            return;
        }

        try {
            app(TaskService::class)->updateStatus($task, new UpdateTaskStatusData(
                status: TaskStatus::from($status)
            ));

            $this->dispatch('taskUpdated');
            $this->loadStats();
            $this->dispatch('toast', text: 'Status actualizado com sucesso.', variant: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', text: 'Erro ao actualizar status.', variant: 'danger');
        }
    }

    public function confirmDelete(int $taskId): void
    {
        $this->taskIdToDelete = $taskId;
        $this->showDeleteModal = true;
    }

    public function deleteTask(): void
    {
        if ($this->taskIdToDelete) {
            $task = auth()->user()->tasks()->find($this->taskIdToDelete);

            if ($task) {
                app(TaskService::class)->delete($task);
                $this->dispatch('taskDeleted');
                $this->loadStats();
                $this->dispatch('toast', text: 'Tarefa eliminada com sucesso.', variant: 'success');
            } else {
                $this->dispatch('toast', text: 'Tarefa não encontrada.', variant: 'danger');
            }
        }

        $this->showDeleteModal = false;
        $this->taskIdToDelete = null;
    }

    public function clearFilters(): void
    {
        $this->reset(['filterStatus', 'filterTitle', 'filterUserId']);
        $this->resetPage();
    }

    public function refreshTasks(): void
    {
        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.task-list', [
            'users' => $this->users,
            'tasks' => $this->tasks,
        ]);
    }
}
