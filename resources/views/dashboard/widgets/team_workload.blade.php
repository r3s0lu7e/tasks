<div class="p-5 h-full">
    @if (auth()->user()->isAdmin() && $teamWorkload->count() > 0)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Team Performance
                    Overview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($teamWorkload->take(4) as $member)
                        <div class="text-center">
                            <div class="h-16 w-16 mx-auto rounded-full flex items-center justify-center mb-2"
                                 style="background-color: rgba({{ $member['status_color_rgb'] }}, 0.2)">
                                <span class="font-medium" style="color: {{ $member['status_color'] }}">
                                    {{ $member['initials'] }}
                                </span>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $member['name'] }}
                            </h4>
                            <div class="mt-2 space-y-1">
                                <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                                    <span>Workload:</span>
                                    <span class="font-medium">{{ $member['workload'] }} tasks</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                                    <span>Completion:</span>
                                    <span class="font-medium">{{ $member['completion_rate'] }}%</span>
                                </div>
                                @if ($member['overdue_count'] > 0)
                                    <div class="flex justify-between text-xs text-red-600 dark:text-red-400">
                                        <span>Overdue:</span>
                                        <span class="font-medium">{{ $member['overdue_count'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
