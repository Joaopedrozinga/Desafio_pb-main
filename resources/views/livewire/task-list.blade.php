<div class="w-full">
    <!-- Welcome Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <flux:heading level="1" class="text-zinc-900 dark:text-white">{{ __('Bem-vindo, ') }}{{ auth()->user()->name }}</flux:heading>
            <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Gere as suas tarefas de forma eficiente') }}</flux:text>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <flux:modal.trigger name="create-task-modal">
                <flux:button variant="primary" class="w-full sm:w-auto" data-test="create-task-button">
                    <flux:icon name="plus" class="me-2" />
                    {{ __('Nova Tarefa') }}
                </flux:button>
            </flux:modal.trigger>

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <flux:button
                    variant="outline"
                    type="submit"
                    class="w-full sm:w-auto"
                    data-test="logout-button"
                >
                    <flux:icon name="arrow-right-start-on-rectangle" class="me-2 size-4" />
                    {{ __('Terminar Sessão') }}
                </flux:button>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4 hover:border-accent/50 transition-colors" :href="route('tasks')" wire:navigate>
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <flux:icon name="clipboard-document-list" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <flux:heading level="3" class="text-zinc-900 dark:text-white text-base">{{ __('Gerir Tarefas') }}</flux:heading>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">{{ __('Ver, criar, editar e eliminar tarefas') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4 hover:border-accent/50 transition-colors" :href="route('profile.edit')" wire:navigate>
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-900/30">
                    <flux:icon name="user" class="size-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div>
                    <flux:heading level="3" class="text-zinc-900 dark:text-white text-base">{{ __('Perfil') }}</flux:heading>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">{{ __('Actualizar o seu perfil e preferências') }}</flux:text>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <flux:heading level="2" class="text-zinc-900 dark:text-white">{{ __('Minhas Tarefas') }}</flux:heading>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" data-test="stats-cards">
        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <flux:icon name="clipboard-document-list" class="size-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <flux:text class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalTasks }}</flux:text>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Total') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                    <flux:icon name="clock" class="size-6 text-yellow-600 dark:text-yellow-400" />
                </div>
                <div>
                    <flux:text class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $pendingTasks }}</flux:text>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Pendentes') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <flux:icon name="arrow-path" class="size-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <flux:text class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $inProgressTasks }}</flux:text>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Em Andamento') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30">
                    <flux:icon name="check-circle" class="size-6 text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <flux:text class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $completedTasks }}</flux:text>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Concluídas') }}</flux:text>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Filters Bar -->
    <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 mb-6">
        <flux:card.header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <flux:heading level="3" class="text-zinc-900 dark:text-white">{{ __('Filtros') }}</flux:heading>
            <flux:button variant="ghost" size="sm" wire:click="clearFilters" wire:loading.attr="disabled">
                <flux:icon name="x-circle" class="me-1" />
                {{ __('Limpar') }}
            </flux:button>
        </flux:card.header>

        <div class="flex flex-col sm:flex-row gap-4 p-4">
            <div class="flex-1">
                <flux:field>
                    <flux:label for="filter-title" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Título') }}</flux:label>
                    <flux:input
                        id="filter-title"
                        wire:model.live.debounce.300ms="filterTitle"
                        placeholder="{{ __('Pesquisar por título...') }}"
                        class="w-full"
                    />
                </flux:field>
            </div>

            <div class="w-full sm:w-48">
                <flux:field>
                    <flux:label for="filter-status" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Status') }}</flux:label>
                    <flux:select
                        id="filter-status"
                        wire:model="filterStatus"
                        class="w-full"
                    >
                        <option value="">{{ __('Todos') }}</option>
                        <option value="pending">{{ __('Pendente') }}</option>
                        <option value="in_progress">{{ __('Em Andamento') }}</option>
                        <option value="completed">{{ __('Concluída') }}</option>
                    </flux:select>
                </flux:field>
            </div>

            <div class="w-full sm:w-48">
                <flux:field>
                    <flux:label for="filter-user" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Utilizador') }}</flux:label>
                    <flux:select
                        id="filter-user"
                        wire:model="filterUserId"
                        class="w-full"
                    >
                        <option value="">{{ __('Todos') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
            </div>
        </div>
    </flux:card>

    <!-- Tasks List -->
    <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="w-full" data-test="tasks-table">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-500 dark:text-zinc-400">{{ __('Tarefa') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-500 dark:text-zinc-400 hidden md:table-cell">{{ __('Descrição') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-500 dark:text-zinc-400 hidden lg:table-cell">{{ __('Criada em') }}</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-zinc-500 dark:text-zinc-400">{{ __('Ações') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($tasks as $task)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors" wire:key="task-{{ $task->id }}">
                            <td class="px-4 py-4">
                                <div class="font-medium text-zinc-900 dark:text-white">{{ $task->title }}</div>
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell text-zinc-500 dark:text-zinc-400 max-w-xs truncate">
                                {{ $task->description ?? '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <flux:badge
                                    :variant="[
                                        'pending' => 'warning',
                                        'in_progress' => 'info',
                                        'completed' => 'success',
                                    ][$task->status->value] ?? 'neutral'"
                                >
                                    {{ $task->status->label() }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-4 hidden lg:table-cell text-zinc-500 dark:text-zinc-400 text-sm">
                                {{ $task->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Status Select -->
                                    <flux:select
                                        wire:model="updateTaskStatus_{{$task->id}}"
                                        wire:change="updateTaskStatus({{$task->id}}, $event.target.value)"
                                        class="w-auto min-w-[140px]"
                                        aria-label="{{ __('Alterar status') }}"
                                    >
                                        <option value="pending" {{ $task->status === \App\Enums\TaskStatus::Pending ? 'selected' : '' }}>
                                            {{ __('Pendente') }}
                                        </option>
                                        <option value="in_progress" {{ $task->status === \App\Enums\TaskStatus::InProgress ? 'selected' : '' }}>
                                            {{ __('Em Andamento') }}
                                        </option>
                                        <option value="completed" {{ $task->status === \App\Enums\TaskStatus::Completed ? 'selected' : '' }}>
                                            {{ __('Concluída') }}
                                        </option>
                                    </flux:select>

                                    <!-- Delete Button -->
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        icon="trash"
                                        wire:click="confirmDelete({{$task->id}})"
                                        class="text-danger-600 dark:text-danger-400 hover:bg-danger-50 dark:hover:bg-danger-900/20"
                                        aria-label="{{ __('Eliminar tarefa') }}"
                                        wire:loading.attr="disabled"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-4 text-zinc-500 dark:text-zinc-400">
                                    <flux:icon name="inbox" class="size-12 opacity-50" />
                                    <div>
                                        <flux:heading level="4">{{ __('Nenhuma tarefa encontrada') }}</flux:heading>
                                        <flux:text>{{ __('Comece criando a sua primeira tarefa.') }}</flux:text>
                                    </div>
                                    <flux:modal.trigger name="create-task-modal">
                                        <flux:button variant="primary">
                                            <flux:icon name="plus" class="me-2" />
                                            {{ __('Criar Tarefa') }}
                                        </flux:button>
                                    </flux:modal.trigger>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($tasks->hasPages())
            <div class="px-4 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $tasks->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </flux:card>

    <!-- Create Task Modal -->
    <flux:modal name="create-task-modal" title="{{ __('Nova Tarefa') }}" subtitle="{{ __('Preencha os campos abaixo para criar uma nova tarefa.') }}">
        <form wire:submit="createTask" class="space-y-4">
            <flux:field>
                <flux:label for="task-title" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Título *') }}</flux:label>
                <flux:input
                    id="task-title"
                    wire:model="form.title"
                    type="text"
                    placeholder="{{ __('Título da tarefa') }}"
                    required
                    autocomplete="off"
                    class="w-full"
                />
                @error('form.title')
                    <flux:text class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</flux:text>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label for="task-description" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Descrição') }}</flux:label>
                <flux:textarea
                    id="task-description"
                    wire:model="form.description"
                    placeholder="{{ __('Descrição opcional...') }}"
                    rows="3"
                    class="w-full"
                />
                @error('form.description')
                    <flux:text class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</flux:text>
                @enderror
            </flux:field>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost" type="button">
                        {{ __('Cancelar') }}
                    </flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto">
                    <flux:icon name="document-plus" class="me-2" />
                    {{ __('Criar') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-task-modal" title="{{ __('Confirmar Eliminação') }}" subtitle="{{ __('Tem a certeza que pretende eliminar esta tarefa? Esta ação não pode ser desfeita.') }}" :show="$showDeleteModal" wire:model="showDeleteModal">
        <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
            <flux:button variant="ghost" type="button" wire:click="showDeleteModal = false">
                {{ __('Cancelar') }}
            </flux:button>
            <flux:button variant="danger" wire:click="deleteTask" wire:loading.attr="disabled">
                <flux:icon name="trash" class="me-2" />
                {{ __('Eliminar') }}
            </flux:button>
        </div>
    </flux:modal>
</div>