<x-layouts::app :title="__('Dashboard')">
@php
    $user = auth()->user();
    $tasks = $user->tasks()->orderBy('created_at', 'desc')->limit(10)->get();
@endphp

<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <flux:heading level="1" class="text-zinc-900 dark:text-white">{{ __('Dashboard') }}</flux:heading>
            <flux:text class="text-zinc-500 dark:text-zinc-400">{{ __('Bem-vindo de volta, ') }}{{ $user->name }}</flux:text>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <flux:button variant="primary" :href="route('tasks')" wire:navigate class="w-full sm:w-auto">
                <flux:icon name="plus" class="me-2 size-4" />
                {{ __('Nova Tarefa') }}
            </flux:button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" data-test="stats-cards">
        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <flux:icon name="clipboard-document-list" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="min-w-0">
                    <flux:text class="text-xl font-bold text-zinc-900 dark:text-white truncate">{{ $user->tasks()->count() }}</flux:text>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Total de Tarefas') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                    <flux:icon name="clock" class="size-5 text-yellow-600 dark:text-yellow-400" />
                </div>
                <div class="min-w-0">
                    <flux:text class="text-xl font-bold text-zinc-900 dark:text-white truncate">{{ $user->tasks()->where('status', \App\Enums\TaskStatus::Pending)->count() }}</flux:text>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Pendentes') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <flux:icon name="arrow-path" class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="min-w-0">
                    <flux:text class="text-xl font-bold text-zinc-900 dark:text-white truncate">{{ $user->tasks()->where('status', \App\Enums\TaskStatus::InProgress)->count() }}</flux:text>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Em Andamento') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30">
                    <flux:icon name="check-circle" class="size-5 text-green-600 dark:text-green-400" />
                </div>
                <div class="min-w-0">
                    <flux:text class="text-xl font-bold text-zinc-900 dark:text-white truncate">{{ $user->tasks()->where('status', \App\Enums\TaskStatus::Completed)->count() }}</flux:text>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Concluídas') }}</flux:text>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4 hover:border-accent/50 transition-colors cursor-pointer" wire:navigate.hover :href="route('tasks')">
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

        <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 p-4 hover:border-accent/50 transition-colors cursor-pointer" wire:navigate.hover :href="route('profile.edit')">
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

    <!-- Tasks Table -->
    <flux:card variant="outline" class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700">
        <flux:card.header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <flux:heading level="3" class="text-zinc-900 dark:text-white">{{ __('Últimas Tarefas') }}</flux:heading>
            <flux:link :href="route('tasks')" wire:navigate class="text-sm">
                {{ __('Ver todas') }}
            </flux:link>
        </flux:card.header>

        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Tarefa') }}</flux:table.column>
                    <flux:table.column class="hidden md:table-cell">{{ __('Descrição') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column class="hidden lg:table-cell">{{ __('Criada') }}</flux:table.column>
                </flux:table.columns>

                @forelse ($tasks as $task)
                    <flux:table.row :key="$task->id">
                        <flux:table.cell>
                            <div class="font-medium text-zinc-900 dark:text-white">{{ $task->title }}</div>
                        </flux:table.cell>
                        <flux:table.cell class="hidden md:table-cell text-zinc-500 dark:text-zinc-400 max-w-xs truncate">
                            {{ $task->description ?? '—' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge
                                :variant="[
                                    \App\Enums\TaskStatus::Pending->value => 'warning',
                                    \App\Enums\TaskStatus::InProgress->value => 'info',
                                    \App\Enums\TaskStatus::Completed->value => 'success',
                                ][$task->status->value] ?? 'neutral'"
                            >
                                {{ $task->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="hidden lg:table-cell text-zinc-500 dark:text-zinc-400 text-sm">
                            {{ $task->created_at->format('d/m/Y') }}
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center py-8">
                            <div class="flex flex-col items-center gap-3 text-zinc-500 dark:text-zinc-400">
                                <flux:icon name="inbox" class="size-10 opacity-50" />
                                <div>
                                    <flux:heading level="4">{{ __('Nenhuma tarefa ainda') }}</flux:heading>
                                    <flux:text>{{ __('Comece criando a sua primeira tarefa.') }}</flux:text>
                                </div>
                                <flux:button variant="primary" :href="route('tasks')" wire:navigate size="sm">
                                    <flux:icon name="plus" class="me-1 size-4" />
                                    {{ __('Criar Tarefa') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table>
        </div>
    </flux:card>
</x-layouts::app>
