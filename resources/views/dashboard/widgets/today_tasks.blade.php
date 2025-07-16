<div class="p-5 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Due Today</h3>
        <a href="{{ route('tasks.index', ['due_date_range' => 'today']) }}"
           class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View
            all</a>
    </div>
    @if ($todayTasks->count() > 0)
        <div class="space-y-2">
            @foreach ($todayTasks->take(3) as $task)
                <div
                     class="p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-800 rounded-md">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="hover:underline text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        {{ $task->title }}
                    </a>
                    <p class="text-xs text-yellow-700 dark:text-yellow-300">
                        @if ($task->assignee)
                            ({{ $task->assignee->name }})
                        @endif
                        for {{ $task->project->name }}
                    </p>
                </div>
            @endforeach
            @if ($todayTasks->count() > 3)
                <div class="text-sm text-yellow-700 dark:text-yellow-300 mt-2">
                    And {{ $todayTasks->count() - 3 }} more...
                </div>
            @endif
        </div>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">No tasks due today.</p>
    @endif
</div>
