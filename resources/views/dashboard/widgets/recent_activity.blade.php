<div class="p-5 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Activity</h3>
        <a href="{{ route('tasks.index') }}"
           class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View
            all</a>
    </div>
    <div class="space-y-3">
        @forelse($recentActivity->take(5) as $task)
            <div
                 class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="flex items-center">
                    <div class="w-2 h-2 rounded-full mr-3" style="background-color: {{ $task->project->color }}"></div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                            <a href="{{ route('tasks.show', $task) }}"
                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                {{ Str::limit($task->title, 25) }}
                            </a>
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $task->project->name }}
                            @if ($task->assignee)
                                • {{ $task->assignee->name }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $task->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end space-y-1">
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                          style="background-color: rgba({{ $task->status->rgb_color }}, 0.2); color: {{ $task->status->color }}">
                        {{ $task->status->name }}
                    </span>
                    <span
                          class="px-2 py-1 text-xs font-medium rounded-full
                        @if ($task->priority === 'low') bg-green-100 dark:bg-green-900 text-green-800 dark:text-white
                        @elseif($task->priority === 'medium') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-white
                        @elseif($task->priority === 'high') bg-red-100 dark:bg-red-900 text-red-800 dark:text-white
                        @elseif($task->priority === 'critical') bg-red-100 dark:bg-red-900 text-red-800 dark:text-white
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent activity
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Task activity will appear
                    here.</p>
            </div>
        @endforelse
    </div>
</div>
