<div class="p-5 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Overdue Tasks</h3>
        <a href="{{ route('tasks.index', ['due_date_range' => 'overdue']) }}"
           class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View
            all</a>
    </div>
    @if ($overdueTasks->count() > 0)
        <div class="space-y-2">
            @foreach ($overdueTasks->take(3) as $task)
                <div class="p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-800 rounded-md">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="hover:underline text-sm font-medium text-red-800 dark:text-red-200">
                        {{ $task->title }}
                    </a>
                    <p class="text-xs text-red-700 dark:text-red-300">
                        @if ($task->assignee)
                            ({{ $task->assignee->name }})
                        @endif
                        - Due: {{ $task->due_date->format('d.m.Y') }}
                    </p>
                </div>
            @endforeach
            @if ($overdueTasks->count() > 3)
                <div class="text-sm text-red-700 dark:text-red-300 mt-2">
                    And {{ $overdueTasks->count() - 3 }} more...
                </div>
            @endif
        </div>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">No overdue tasks.</p>
    @endif
</div>
