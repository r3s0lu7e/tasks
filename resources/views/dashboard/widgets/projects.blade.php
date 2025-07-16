<div class="p-5 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Active Projects</h3>
        <a href="{{ route('projects.index') }}"
           class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View
            all</a>
    </div>
    <div class="space-y-4">
        @forelse($projects->take(5) as $project)
            <div
                 class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $project->color }}"></div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                            <a href="{{ route('projects.show', $project) }}"
                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                {{ $project->name }}
                            </a>
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $project->tasks_count }} tasks •
                            {{ $project->members_count }} members</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-center space-x-2 mb-1">
                        <span
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $project->status === 'active' ? 'green' : 'gray' }}-100 dark:bg-{{ $project->status === 'active' ? 'green' : 'gray' }}-900 text-{{ $project->status === 'active' ? 'green' : 'gray' }}-800 dark:text-{{ $project->status === 'active' ? 'green' : 'gray' }}-200">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                    <div class="w-20 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full"
                             style="width: {{ $project->calculated_progress }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $project->calculated_progress }}%
                        complete</p>
                </div>
            </div>
        @empty
            <div class="text-center py-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No projects yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your first project to
                    get started.</p>
                <div class="mt-6">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        Create Project
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
